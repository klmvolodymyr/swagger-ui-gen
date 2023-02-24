<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Bundle\ModelHandler\Schema;

class ValidatorBuilder implements SchemaBuilderInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $classMetadataFactory;

    /**
     * @var SchemaFactory
     */
    private $schemaFactory;

    /**
     * ValidatorBuilder constructor.
     *
     * @param MetadataFactoryInterface $classMetadataFactory
     * @param SchemaFactory            $schemaFactory
     */
    public function __construct(MetadataFactoryInterface $classMetadataFactory, SchemaFactory $schemaFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
        $this->schemaFactory = $schemaFactory;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 20;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'validator';
    }

    /**
     * @param Schema         $schema
     * @param ConfigRegistry $configRegistry
     */
    public function buildSchema(Schema $schema, ConfigRegistry $configRegistry): void
    {
        $config = $configRegistry[$schema] ?? [];
        if (!isset($config['mapping']['class'])) {
            return;
        }

        $class = $config['mapping']['class'];
        $validationMetadata = $this->classMetadataFactory->getMetadataFor($class);

        if (!$validationMetadata instanceof ClassMetadata) {
            return;
        }

        $groups = $this->getGroups($schema, $configRegistry);
        foreach ($validationMetadata->properties as $propertyName => $propertyMetadata) {
            $this->buildPropertySchema($schema, $propertyName, $propertyMetadata, $groups);
        }
    }

    /**
     * @param Schema         $schema
     * @param ConfigRegistry $configRegistry
     *
     * @return array
     */
    private function getGroups(Schema $schema, ConfigRegistry $configRegistry): array
    {
        $currentParent = $schema->getParent();
        $groups = $configRegistry[$schema]['mapping']['validation_groups'] ?? [];
        while ($currentParent !== null) {
            $parentGroups = $configRegistry[$currentParent]['mapping']['validation_groups'] ?? [];
            $groups = array_merge($groups, $parentGroups);
            $currentParent = $currentParent->getParent();
        }

        return $groups;
    }

    /**
     * @param Schema           $parentSchema
     * @param string           $propertyName
     * @param PropertyMetadata $metadata
     * @param array            $groups
     */
    private function buildPropertySchema(Schema $parentSchema, string $propertyName, PropertyMetadata $metadata, array $groups): void
    {
        $propertySchema = $this->schemaFactory->getChildPropertySchema($parentSchema, $propertyName);
        $builders = $this->getConstraintBuilders();
        foreach ($this->getConstraints($metadata, $groups) as $constraint) {
            $constraintClass = get_class($constraint);
            foreach ($builders as $builderConstraintClass => $builder) {
                if ($constraintClass === $builderConstraintClass || is_subclass_of($constraint, $constraintClass)) {
                    call_user_func_array($builder, [$propertySchema, $constraint]);
                }
            }
        }
    }

    /**
     * @param PropertyMetadata $propertyMetadata
     * @param array            $groups
     *
     * @return array|Constraint[]
     */
    private function getConstraints(PropertyMetadata $propertyMetadata, array $groups): array
    {
        if (count($groups) === 0) {
            return $propertyMetadata->getConstraints();
        }

        $constraints = [];
        foreach ($groups as $group) {
            if (is_array($propertyMetadata)) {
                /* @var $propertyMetadata PropertyMetadataInterface[] */
                foreach ($propertyMetadata as $item) {
                    $constraints = array_merge($constraints, $item->findConstraints($group));
                }
            } else {
                $constraints = array_merge($constraints, $propertyMetadata->findConstraints($group));
            }
        }

        return $constraints;
    }

    /**
     * @return array
     */
    private function getConstraintBuilders(): array
    {
        return [
            Constraints\NotBlank::class => function (Schema $schema) {
                if ($parent = $schema->getParent()) {
                    $parent->addRequired($schema->getNameOnParentProperties());
                }
            },
            Constraints\Regex::class => function (Schema $schema, Constraints\Regex $constraint) {
                $schema->setPattern($constraint->pattern);
            },
            Constraints\Choice::class => function (Schema $schema, Constraints\Choice $constraint) {
                $schema->setEnum($constraint->choices);
            },
            Constraints\Length::class => function (Schema $schema, Constraints\Length $constraint) {
                if (!is_null($constraint->max)) {
                    $schema->setMinimum($constraint->min);
                }
                if (!is_null($constraint->max)) {
                    $schema->setMaximum($constraint->max);
                }
            },
            Constraints\Type::class => function (Schema $schema, Constraints\Type $constraint) {
                $schema->setType($constraint->type);
            },
            Constraints\Date::class => function (Schema $schema) {
                $schema->setFormat(DataTypeFormat::FORMAT_DATE);
            },
            Constraints\DateTime::class => function (Schema $schema) {
                $schema->setFormat(DataTypeFormat::FORMAT_DATETIME);
            },
            Constraints\Time::class => function (Schema $schema) {
                $schema->setFormat(DataTypeFormat::FORMAT_TIME);
            },
            Constraints\Email::class => function (Schema $schema) {
                $schema->setFormat(DataTypeFormat::FORMAT_EMAIL);
            },
            Constraints\Ip::class => function (Schema $schema, Constraints\Ip $constraint) {
                if (Constraints\Ip::V4 === $constraint->version) {
                    $schema->setFormat(DataTypeFormat::FORMAT_IPADDRESS);
                } elseif (Constraints\Ip::V6 === $constraint->version) {
                    $schema->setFormat(DataTypeFormat::FORMAT_IPV6);
                }
            },
        ];
    }
}