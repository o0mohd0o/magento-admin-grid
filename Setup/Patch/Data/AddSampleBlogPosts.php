<?php
/**
 * Sample data patch for blog posts
 */
declare(strict_types=1);

namespace Itcforu\BlogPost\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddSampleBlogPosts implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        
        $data = [
            [
                'title' => 'First Blog Post',
                'content' => 'This is the content of the first blog post. It demonstrates how to create and manage blog posts in Magento 2.',
                'author' => 'John Doe',
                'status' => 1
            ],
            [
                'title' => 'Second Blog Post',
                'content' => 'This is the content of the second blog post. It shows how to work with the admin grid in Magento 2.',
                'author' => 'Jane Smith',
                'status' => 1
            ],
            [
                'title' => 'Draft Blog Post',
                'content' => 'This is a draft blog post that is not published yet.',
                'author' => 'Admin User',
                'status' => 0
            ]
        ];
        
        $this->moduleDataSetup->getConnection()->insertMultiple(
            $this->moduleDataSetup->getTable('itcforu_blogpost'),
            $data
        );
        
        $this->moduleDataSetup->endSetup();
        
        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
