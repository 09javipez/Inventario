<div x-data="{
   products: @entangle('products'),
   total: @entangle('total'),

   removeProduct(index) {
        this.products.splice(index, 1);
   },
   init()
   {
        this.$watch('products', (newProducts) => {
            let total = 0;
            newProducts.forEach(product => {
                let base =
                    product.quantity * product.price;
                let subtotalWithoutTax =
                    base - (product.discount || 0);
                let tax =
                    subtotalWithoutTax *
                    ((product.tax_rate || 0) /100);
                let subtotal =
                    subtotalWithoutTax + tax;
                total += subtotal;
            });
            this.total = total;
        });
   }
}">
    <x-wire-card>
        <form wire:submit.prevent="save" class="space-y-4">

            <div class="grid lg:grid-cols-4 gap-4">
                <x-wire-native-select
                    label="Tipo de Comprobante"
                    wire:model="voucher_type"
                >

                    <option value="1">Factura</option>
                    <option value="2">Boleta</option>

                </x-wire-native-select>
                <div class="grid grid-cols-2 gap-2">
                    <x-wire-input
                        label="Serie"
                        wire:model="serie"
                        placeholder="Serie del comprobante"
                    />
                    <x-wire-input
                        label="Correlativo"
                        wire:model="correlative"
                        placeholder="Correlativo del comprobante"
                    />
                </div>
                <x-wire-input
                    label="Fecha"
                    wire:model="date"
                    type="date"
                />
                <x-wire-select
                    label="Orden de compra"
                    wire:model.live="purchase_order_id"
                    placeholder="Selecciona una orden de compra"
                    :async-data="[
                    'api' => route('api.purchase-orders.index'),
                        'method' => 'POST',

                    ]"
                    option-label="name"
                    option-value="id"
                    option-description="description"
                />
                <div class="col-span-2">
                    <x-wire-select
                        label="Proveedor"
                        wire:model="supplier_id"
                        placeholder="Selecciona un proveedor"
                        :async-data="[
                        'api' => route('api.suppliers.index'),
                            'method' => 'POST',

                        ]"
                        option-label="name"
                        option-value="id"
                    />
                </div>
                <div class="col-span-2">
                    <x-wire-select
                        label="Almacenes"
                        wire:model="warehouse_id"
                        placeholder="Selecciona un proveedor"
                        :async-data="[
                        'api' => route('api.warehouses.index'),
                            'method' => 'POST',

                        ]"
                        option-label="name"
                        option-value="id"
                        option-description="description"
                        :disabled="count($products)"
                    />
                </div>
            </div>
            <div class="lg:flex lg:space-x-4">
                <x-wire-select
                    label="Producto"
                    wire:model="product_id"
                    placeholder="Selecciona un producto"
                    :async-data="[
                    'api' => route('api.products.index'),
                        'method' => 'POST',

                    ]"
                    option-label="name"
                    option-value="id"
                    class="flex-1"
                />
                <div class="flex-shrink-0">
                    <x-wire-button
                    wire:click="addProduct"
                    spinner="addProduct"
                    class="w-full mt-4 lg:mt-6.5">
                        Agregar producto
                    </x-wire-button>
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-700 border-y bg-black-800">
                            <th class="py-2 px-4">
                                Producto
                            </th>
                            <th class="py-2 px-4">
                                Descripcion
                            </th>
                            <th class="py-2 px-4">
                                Cantidad
                            </th>
                            <th class="py-2 px-4">
                                Valor unitario
                            </th>
                            <th class="py-2 px-4">
                                Descuento
                            </th>
                            <th class="py-2 px-4">
                                IVA %
                            </th>
                            <th class="py-2 px-4">
                                Impuesto
                            </th>
                            <th class="py-2 px-4">
                                Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <template x-for="(product, index) in products" :key="product.id + '-' + index">
                            <tr class="border-b">
                                <td class="px-4 py-1"
                                    x-text="product.name">
                                </td>
                                <td class="px-4 py-1"
                                    x-text="product.description">
                                </td>
                                <td class="px-4 py-1">
                                    <x-wire-input
                                    x-model.number="product.quantity"
                                    type="number"
                                    class="w-20"
                                    />
                                </td>
                                <td class="px-4 py-1">
                                    <x-wire-input
                                    x-model.number="product.price"
                                    type="number"
                                    class="w-20"
                                    step="0.01"
                                    />
                                </td>
                                <td class="px-4 py-1">
                                    <x-wire-input
                                    x-model.number="product.discount"
                                    type="number"
                                    class="w-24"
                                    min="0"
                                    />
                                </td>
                                <td class="px-4 py-1">
                                    <x-wire-native-select
                                        x-model.number="product.tax_rate"
                                        class="w-24"
                                    >
                                    <option value="0">0%</option>
                                    <option value="5">5%</option>
                                    <option value="19">19%</option>
                                    </x-wire-native-select>
                                </td>
                                {{-- Impuesto --}}
                                <td class="px-4 py-1">
                                    <span
                                        x-text="(((product.quantity * product.price)
                                        -(product.discount || 0
                                         )
                                        )
                                         *
                                         (
                                            (product.tax_rate || 0
                                            ) / 100
                                          )
                                        ).toLocaleString(
                                        'es-CO',
                                            {
                                                minimumFractionDigits:2,
                                                maximumFractionDigits:2
                                            }
                                        )
                                        "
                                    ></span>
                                </td>
                                <td class="px-4 py-1">
                                    <span
                                        x-text="
                                            (
                                                (
                                                    (product.quantity * product.price)
                                                    - (product.discount || 0)
                                                )
                                                *
                                                (1 + ((product.tax_rate || 0) / 100))
                                            ).toLocaleString(
                                                'es-CO',
                                                {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                }
                                            )
                                        "
                                    ></span>
                                </td>
                                <td class="px-4 py-1">
                                    <x-wire-mini-button
                                        rounded
                                        x-on:click="removeProduct(index)"
                                        icon="trash"
                                        red
                                    />
                                </td>
                            </tr>
                        </template>
                        <template x-if="products.length === 0">
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">
                                    No hay productos agregados.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="flex items-center space-x-4">

                <x-label>
                    observaciones
                </x-label>

                <x-wire-input
                    class="flex-1"
                   wire:model="observation"
                />

                <div>
                    Total: $ <span x-text="total.toLocaleString('es-CO', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })">
                    </span>
                </div>
            </div>
            <div class="flex justify-end">
                <x-wire-button
                    type="submit"
                    icon="check"
                    spinner="save"
                    >
                    Guardar
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>
</div>
