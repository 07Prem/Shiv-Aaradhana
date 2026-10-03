<?php

namespace Tests\Feature;

use App\Models\AttributeDefinition;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductAttributeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Category $category;
    protected ProductType $productType;
    protected AttributeDefinition $attrPurity;
    protected AttributeDefinition $attrMoisture;
    protected AttributeDefinition $attrAdmixture;
    protected AttributeDefinition $attrSortex;
    protected AttributeDefinition $attrUnused;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Quality Admin',
            'email' => 'admin@shivaaradhana.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Spices & Oilseeds',
            'slug' => 'spices-and-oilseeds',
            'is_active' => true,
        ]);

        $this->productType = ProductType::create([
            'category_id' => $this->category->id,
            'name' => 'Cumin Seeds',
            'slug' => 'cumin-seeds',
            'is_active' => true,
        ]);

        $this->attrPurity = AttributeDefinition::create([
            'name' => 'Purity',
            'code' => 'purity',
            'type' => 'number',
            'unit' => '%',
            'sort_order' => 1,
        ]);

        $this->attrMoisture = AttributeDefinition::create([
            'name' => 'Moisture',
            'code' => 'moisture',
            'type' => 'number',
            'unit' => '%',
            'sort_order' => 2,
        ]);

        $this->attrAdmixture = AttributeDefinition::create([
            'name' => 'Admixture / Foreign Matter',
            'code' => 'admixture',
            'type' => 'number',
            'unit' => '%',
            'sort_order' => 3,
        ]);

        $this->attrSortex = AttributeDefinition::create([
            'name' => 'Sortex Grading',
            'code' => 'sortex_grading',
            'type' => 'text',
            'unit' => null,
            'sort_order' => 4,
        ]);

        $this->attrUnused = AttributeDefinition::create([
            'name' => 'Aflatoxin Limit',
            'code' => 'aflatoxin_limit',
            'type' => 'text',
            'unit' => 'ppb',
            'sort_order' => 5,
        ]);
    }

    public function test_attribute_value_model_preserves_numeric_precision_and_zero(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'product_type_id' => $this->productType->id,
            'name' => 'Test Cumin Seeds',
            'slug' => 'test-cumin-seeds',
            'origin' => 'Gujarat, India',
            'short_description' => 'Test description',
            'description' => 'Full test description',
            'status' => Product::STATUS_PUBLISHED,
        ]);

        // 1. Value with decimal precision: '12.50'
        $valPrecision = ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrMoisture->id,
            'text_value' => '12.50',
            'number_value' => 12.5,
        ]);

        $this->assertSame('12.50', $valPrecision->input_value);
        $this->assertSame('12.50 %', $valPrecision->formatted_value);

        // 2. Zero value: '0'
        $valZero = ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrAdmixture->id,
            'text_value' => '0',
            'number_value' => 0.0,
        ]);

        $this->assertSame('0', $valZero->input_value);
        $this->assertSame('0 %', $valZero->formatted_value);

        // 3. Fallback when text_value is null but number_value exists (e.g. 0.0000)
        $valNumOnly = ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrPurity->id,
            'text_value' => null,
            'number_value' => 0.0000,
        ]);

        $this->assertSame('0', $valNumOnly->input_value);
        $this->assertSame('0 %', $valNumOnly->formatted_value);

        // 4. Text value with existing unit (e.g. '99.90%') on a second product does not duplicate unit
        $product2 = Product::create([
            'category_id' => $this->category->id,
            'product_type_id' => $this->productType->id,
            'name' => 'Test Fennel Seeds',
            'slug' => 'test-fennel-seeds',
            'origin' => 'Gujarat, India',
            'short_description' => 'Test description',
            'description' => 'Full test description',
            'status' => Product::STATUS_PUBLISHED,
        ]);

        $valTextUnit = ProductAttributeValue::create([
            'product_id' => $product2->id,
            'attribute_definition_id' => $this->attrPurity->id,
            'text_value' => '99.90%',
            'number_value' => 99.9,
        ]);

        $this->assertSame('99.90%', $valTextUnit->input_value);
        $this->assertSame('99.90%', $valTextUnit->formatted_value);
    }

    public function test_admin_edit_page_renders_safely_without_undefined_array_keys(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'product_type_id' => $this->productType->id,
            'name' => 'Premium Jeera',
            'slug' => 'premium-jeera',
            'origin' => 'Unjha, Gujarat',
            'short_description' => 'Export cumin.',
            'description' => 'Unjha benchmark cumin.',
            'status' => Product::STATUS_PUBLISHED,
        ]);

        // Populate only moisture with decimal precision and admixture with zero
        ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrMoisture->id,
            'text_value' => '12.50',
            'number_value' => 12.5,
        ]);

        ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrAdmixture->id,
            'text_value' => '0',
            'number_value' => 0,
        ]);

        // attrPurity, attrSortex, and attrUnused have NO rows in product_attribute_values
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.edit', $product));

        $response->assertStatus(200);

        // Verify input fields rendered with expected values
        $response->assertSee('name="attributes[' . $this->attrMoisture->id . ']"', false);
        $response->assertSee('value="12.50"', false);

        $response->assertSee('name="attributes[' . $this->attrAdmixture->id . ']"', false);
        $response->assertSee('value="0"', false);

        // Verify unassigned attribute renders safely without undefined array key warnings
        $response->assertSee('name="attributes[' . $this->attrUnused->id . ']"', false);
        $response->assertSee('Aflatoxin Limit (ppb)');
    }

    public function test_product_update_handles_text_numeric_precision_zero_and_blank_deletion(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'product_type_id' => $this->productType->id,
            'name' => 'Export Sesame Seeds',
            'slug' => 'export-sesame-seeds',
            'origin' => 'Gujarat, India',
            'short_description' => 'Natural white sesame.',
            'description' => 'Sortex cleaned sesame seeds.',
            'status' => Product::STATUS_PUBLISHED,
        ]);

        // Seed initial attribute that should be deleted when submitted blank
        ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrSortex->id,
            'text_value' => 'Initial Sortex Text',
            'number_value' => null,
        ]);

        $updatePayload = [
            'category_id' => $this->category->id,
            'product_type_id' => $this->productType->id,
            'name' => 'Export Sesame Seeds Updated',
            'slug' => 'export-sesame-seeds',
            'origin' => 'Gujarat, India',
            'short_description' => 'Natural white sesame updated.',
            'description' => 'Sortex cleaned sesame seeds updated.',
            'status' => Product::STATUS_PUBLISHED,
            'attributes' => [
                $this->attrPurity->id => '99.90',      // Decimal precision with trailing zero
                $this->attrMoisture->id => '12.50',    // Decimal precision
                $this->attrAdmixture->id => '0',       // Explicit zero
                $this->attrSortex->id => '',           // Blank -> should delete existing row
                $this->attrUnused->id => '   ',        // Whitespace only -> should not create row
            ],
        ];

        $response = $this->actingAs($this->adminUser)->put(route('admin.products.update', $product), $updatePayload);

        $response->assertRedirect(route('admin.products.index'));

        // 1. Verify '99.90' precision preserved
        $this->assertDatabaseHas('product_attribute_values', [
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrPurity->id,
            'text_value' => '99.90',
            'number_value' => 99.9,
        ]);

        // 2. Verify '12.50' precision preserved
        $this->assertDatabaseHas('product_attribute_values', [
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrMoisture->id,
            'text_value' => '12.50',
            'number_value' => 12.5,
        ]);

        // 3. Verify zero '0' is saved and NOT deleted
        $this->assertDatabaseHas('product_attribute_values', [
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrAdmixture->id,
            'text_value' => '0',
            'number_value' => 0.0,
        ]);

        // 4. Verify blank field was deleted
        $this->assertDatabaseMissing('product_attribute_values', [
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrSortex->id,
        ]);

        // 5. Verify whitespace-only field was not created
        $this->assertDatabaseMissing('product_attribute_values', [
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrUnused->id,
        ]);
    }

    public function test_public_specifications_table_displays_precision_and_zero_while_omitting_blank_attributes(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'product_type_id' => $this->productType->id,
            'name' => 'Sortex Cumin Benchmark',
            'slug' => 'sortex-cumin-benchmark',
            'origin' => 'Gujarat, India',
            'short_description' => 'Lab tested cumin.',
            'description' => 'Complete laboratory specifications sheet.',
            'status' => Product::STATUS_PUBLISHED,
        ]);

        ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrMoisture->id,
            'text_value' => '12.50',
            'number_value' => 12.5,
        ]);

        ProductAttributeValue::create([
            'product_id' => $product->id,
            'attribute_definition_id' => $this->attrAdmixture->id,
            'text_value' => '0',
            'number_value' => 0.0,
        ]);

        // Public detail page route is catalog.product
        $response = $this->get(route('catalog.product', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Technical & Quality Specifications', false);
        $response->assertSee('Moisture');
        $response->assertSee('12.50 %');
        $response->assertSee('Admixture / Foreign Matter');
        $response->assertSee('0 %');

        // Unused attribute should not appear in the table
        $response->assertDontSee('Aflatoxin Limit');
    }
}
