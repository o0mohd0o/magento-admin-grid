# Visual Aid for Lesson 2: Database Setup

## Database Schema Diagram

```
+------------------------+
| itcforu_blogpost       |
+------------------------+
| post_id (PK)           | <-- Primary Key (Auto-increment)
| title                  | <-- Indexed for faster searches
| content                |
| author                 |
| created_at             | <-- Auto-populated timestamp
| updated_at             | <-- Auto-updated timestamp
| status                 | <-- 1=Published, 0=Draft
+------------------------+
```

## Column Types Explained

| Column     | Type      | Description                                          |
|------------|-----------|------------------------------------------------------|
| post_id    | int       | Unique identifier for each blog post                 |
| title      | varchar   | Limited-length string (max 255 characters)           |
| content    | text      | Unlimited-length text field for post content         |
| author     | varchar   | Limited-length string (max 255 characters)           |
| created_at | timestamp | Date and time when the post was created              |
| updated_at | timestamp | Date and time when the post was last updated         |
| status     | smallint  | Small integer value (1=Published, 0=Draft)           |

## Declarative Schema vs. Setup Scripts

### Old Approach (Setup Scripts)
```php
// InstallSchema.php
$table = $setup->getConnection()->newTable(
    $setup->getTable('itcforu_blogpost')
)->addColumn(
    'post_id',
    Table::TYPE_INTEGER,
    null,
    ['identity' => true, 'nullable' => false, 'primary' => true],
    'Post ID'
)
// ... more columns
$setup->getConnection()->createTable($table);

// UpgradeSchema.php (if adding columns later)
$setup->getConnection()->addColumn(
    $setup->getTable('itcforu_blogpost'),
    'new_column',
    [
        'type' => Table::TYPE_TEXT,
        'nullable' => true,
        'comment' => 'New Column'
    ]
);
```

### New Approach (Declarative Schema)
```xml
<!-- db_schema.xml -->
<table name="itcforu_blogpost">
    <column name="post_id" xsi:type="int" identity="true" nullable="false"/>
    <!-- ... more columns -->
    
    <!-- To add a new column, just add it here -->
    <column name="new_column" xsi:type="text" nullable="true" comment="New Column"/>
</table>
```

## Data Patch Flow

```
+-------------------+      +-------------------+      +-------------------+
| setup:upgrade     | ---> | Data Patch        | ---> | Database          |
| command executed  |      | apply() method    |      | Sample data added |
+-------------------+      +-------------------+      +-------------------+
                                    |
                                    v
                           +-------------------+
                           | patch_list table  |
                           | Patch recorded    |
                           +-------------------+
```

## Key Commands

```bash
# Apply schema changes and data patches
bin/magento setup:upgrade

# Compile dependency injection configuration
bin/magento setup:di:compile

# Clear cache to see changes
bin/magento cache:clean
```
