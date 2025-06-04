# D7 Migration

This custom module provides migration configuration for moving data from a Drupal 7 site.

## Requirements

Enable the following contributed modules before using this module:

- `migrate_plus`
- `migrate_tools`
- `migrate_drupal`
- `migrate_drupal_ui`

## Installation

Use Drush to enable all required modules along with this one:

```
drush en migrate_plus migrate_tools migrate_drupal migrate_drupal_ui d7_migration -y
```

## Configuration

Add a database connection named `drupal7` in your `settings.php` pointing to the source Drupal 7 database.
