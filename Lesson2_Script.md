# Magento 2 Admin Grid Course - Lesson 2: Database Setup

## Video Script (20 minutes)

### Introduction (2 minutes)

**[On Camera]**

"Hello everyone, and welcome back to our course on creating a Magento 2 Admin Grid. In the previous lesson, we set up our module structure with the basic configuration files. Today, we'll be diving into Lesson 2: Database Setup.

In this lesson, we'll learn how to:
- Create a database schema using Magento 2's declarative schema approach
- Define table structures and relationships
- Set up data fixtures to populate our table with sample data

Let's get started!"

### Understanding Declarative Schema (3 minutes)

**[Slide: Declarative Schema Overview]**

"Before we dive into the code, let's understand what declarative schema is. In Magento 2.3 and later, Magento introduced a declarative approach to database schema management.

Instead of using install and upgrade scripts, we now define our database structure in XML format. This approach has several advantages:

1. It's more maintainable - you can see the entire database structure in one file
2. It's more reliable - Magento handles the upgrade process automatically
3. It's reversible - you can remove columns or tables by simply removing them from the schema

The main file we'll be working with is called `db_schema.xml` and it lives in the `etc` directory of our module."

### Creating the db_schema.xml File (5 minutes)

**[Screen Share: Code Editor]**

"Now, let's create our database schema file. I'll navigate to our module's `etc` directory and create a new file called `db_schema.xml`.

In this file, we'll define a table for our blog posts with the following columns:
- post_id (Primary Key)
- title
- content
- author
- created_at
- updated_at
- status

Let me walk you through the code:"

**[Show and explain the db_schema.xml file]**

```xml
<?xml version="1.0"?>
<schema xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Setup/Declaration/Schema/etc/schema.xsd">
    <table name="itcforu_blogpost" resource="default" engine="innodb" comment="Blog Post Table">
        <column xsi:type="int" name="post_id" padding="10" unsigned="true" nullable="false" identity="true"
                comment="Post ID"/>
        <column xsi:type="varchar" name="title" nullable="false" length="255" comment="Post Title"/>
        <column xsi:type="text" name="content" nullable="true" comment="Post Content"/>
        <column xsi:type="varchar" name="author" nullable="true" length="255" comment="Post Author"/>
        <column xsi:type="timestamp" name="created_at" on_update="false" nullable="false" default="CURRENT_TIMESTAMP"
                comment="Post Creation Time"/>
        <column xsi:type="timestamp" name="updated_at" on_update="true" nullable="false" default="CURRENT_TIMESTAMP"
                comment="Post Modification Time"/>
        <column xsi:type="smallint" name="status" padding="5" unsigned="true" nullable="false" default="1"
                comment="Post Status"/>
        <constraint xsi:type="primary" referenceId="PRIMARY">
            <column name="post_id"/>
        </constraint>
        <index referenceId="ITCFORU_BLOGPOST_TITLE" indexType="btree">
            <column name="title"/>
        </index>
    </table>
</schema>
```

"Let me break down the key components:

1. The `<schema>` tag is the root element that references the schema XSD.
2. The `<table>` tag defines our blog post table:
   - `name`: The table name in the database (always use a vendor prefix)
   - `resource`: The database connection to use
   - `engine`: The MySQL storage engine
   - `comment`: A description of the table

3. Each `<column>` tag defines a column in our table:
   - `xsi:type`: The data type (int, varchar, text, timestamp, etc.)
   - `name`: The column name
   - `nullable`: Whether the column can be NULL
   - `identity`: For auto-increment columns
   - Other attributes specific to the data type

4. The `<constraint>` tag defines our primary key.

5. The `<index>` tag creates an index on the title column for faster searches.

This structure gives us a complete blog post table that we can use for our admin grid."

### Understanding Column Types (3 minutes)

**[Slide: Common Column Types]**

"Let's take a moment to discuss some common column types you might use in your own projects:

1. `int` - For integer values like IDs and counts
2. `smallint` - For smaller integer values like status flags
3. `varchar` - For string values with a fixed maximum length
4. `text` - For longer text content with no fixed length
5. `decimal` - For precise decimal numbers like prices
6. `timestamp` - For date and time values
7. `boolean` - For true/false values

Choose the appropriate type based on the data you need to store. Using the right type helps with data integrity and performance."

### Creating Data Fixtures (5 minutes)

**[Screen Share: Code Editor]**

"Now that we have our table structure defined, let's create some sample data to populate our table. In Magento 2, we use data patches for this purpose.

Data patches are PHP classes that run once during the setup:upgrade process and are tracked in the `patch_list` table to ensure they don't run again.

Let's create a data patch to add some sample blog posts:"

**[Show and explain the AddSampleBlogPosts.php file]**

```php
<?php
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
```

"Let me explain the key parts of this data patch:

1. The class implements `DataPatchInterface`, which requires three methods:
   - `apply()`: Contains the code to add our sample data
   - `getDependencies()`: Lists other patches that must run before this one
   - `getAliases()`: Lists alternative names for this patch (for backward compatibility)

2. In the `apply()` method:
   - We start by calling `startSetup()`
   - We define an array of sample blog posts
   - We use `insertMultiple()` to add all posts at once
   - We finish with `endSetup()`
   - We return `$this` as required by the interface

This data patch will run once when we run the `setup:upgrade` command, adding our sample blog posts to the database."

### Applying the Schema and Data (2 minutes)

**[Screen Share: Terminal]**

"Now that we have our schema and data patch files in place, let's apply them to the database. We'll run the following commands:

```bash
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
```

The `setup:upgrade` command will:
1. Detect our new schema file and create/modify the database table
2. Run our data patch to add the sample blog posts
3. Update the module version in the database

Let's run these commands and see the results."

**[Run the commands and show the successful output]**

"Great! Our database table has been created and populated with sample data. We can verify this by checking the database directly."

**[Optional: Show the table in a database client]**

### Conclusion and Next Steps (2 minutes)

**[On Camera]**

"Today, we've learned how to set up the database structure for our Magento 2 admin grid module. We've created a declarative schema file to define our table structure and a data patch to add sample data.

In the next lesson, we'll create the Model, ResourceModel, and Collection classes to interact with our database table. These classes will form the foundation of our CRUD operations.

Thank you for watching, and I'll see you in the next lesson!"

**[End Screen with Call to Action]**

"If you found this video helpful, please like and subscribe for more Magento 2 development tutorials. Leave a comment if you have any questions, and I'll do my best to answer them.

See you in Lesson 3!"
