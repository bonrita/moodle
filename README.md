Moodle

A drupal 8 module that displays various data from moodle into drupal e.g users, courses etc....
It intergrates the moodle database to Drupal 8 views core module.

## Prerequisites

  * Drupal 8

## Installation

### Step 1: Clone repository.

Create a folder named 'moodle' in the 'modules' folder

    git clone https://github.com/bonrita/moodle.git moodle

### Step 2: Add the moodle database settings.

Navigate to 'sites/default/settings.php' of your drupal 8 installation.
Add the configurations below:

    $databases['moodle']['default'] = array (
      'database' => 'moodle database',
      'username' => 'moodle database user',
      'password' => 'moodle database password',
      'prefix' => 'mdl_',
      'host' => '127.0.0.1',
      'port' => '3306',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
    );

### Step 3: Install the module.
Navigate to 'admin/modules' path of your drupal 8 installation to enable the module.

### Step 4: Configure the module.
Navigate to 'admin/config/moodle/connection-settings' path of your drupal 8 installation.
Fill out the necessary information.

### Step 5: Map the drupal users to moodle users.
For each user navigate to 'user/%/moodle'
where '%' is the user id of the user you want to map.

Select the moodle user from the drop down that you want to map to the user in question.

#### Step 6: Create a moodle course view page or any other view type display.
Navigate to 'admin/structure/views' add a new view.
From  'View settings' select 'Moodle course'

You may add arguments e.g select courses of an in logged user or any other user.
You may filter on categories of courses.
Just dive in and make your fingers wet. You will find many Goodies.


## Coming soon on the list.

- Views field to embed the moodle view in a fieldable entity e.g node, user, paragraph etc....

    This functionality may later be removed or ignored if the [viewfield module](https://www.drupal.org/project/views_field) is ready for use
    in drupal 8. At the moment it will be called the moodle_view_field but it may also be used to embed any other kind of view.

- Add tests for all the functionality in the moodle module.
- Link courses to the moodle courses in the moodle e-learning application.
- Expose more course data to views e.g course summary.

