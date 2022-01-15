<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigurationsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ModelHasPermissionsTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(ConfigurationCategoryTableSeeder::class);
        $this->call(DistrictsTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(ProductCategoriesTableSeeder::class);
//        $this->call(SupplierTableSeeder::class);
     //   $this->call(CustomerTableSeeder::class);
//        $this->call(VehicleTypesTableSeeder::class);
        $this->call(ProductStoreLocationsTableSeeder::class);
        $this->call(BranchesTableSeeder::class);
     //   $this->call(ProductionProductRelatedConsumersTableSeeder::class);
    }
}
