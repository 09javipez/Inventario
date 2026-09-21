<x-admin-layout
 title="Productos | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Productos',
    'href' => ('admin.products.index'),
],
[
    'name' => 'Editar'
]
]"
>
    {{-- Aqui este metodo es para incluir imagenes donde queramos --}}
    @push('css')
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endpush

    <div class="mb-4">
        <form action="{{ route('admin.products.dropzone',$product) }}" class="dropzone" id="my-dropzone" method="POST" >
            @csrf


        </form>
    </div>

    <x-wire-card>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-4">

            @csrf
            @method('PUT')

            <x-wire-input
                label="Nombre"
                name="name"
                placeholder="Nombre de la categoría"
                value="{{ old('name',$product->name) }}"
            />
            <x-wire-textarea
                label="Descripcíon"
                name="description"
                placeholder="Descripción de la categoría">
                {{ old('description',$product->description) }}
            </x-wire-textarea>
            <x-wire-input
                type="number"
                label="Precio"
                name="price"
                placeholder="Precio del producto"
                value="{{ old('price',$product->price) }}"
            />

            <x-wire-native-select
                label="Categoría"
                name="category_id"
            >
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id',$product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-wire-native-select>

            {{-- Minimo stock --}}
            <x-wire-input
                label="Stock minimo"
                name="min_stock"
                type="number"
                placeholder="Cantidad minima de stock"
                value="{{ old('min_stock', $product->min_stock) }}"
            />

            <div class="flex justify-end">
                <x-button>
                    Actualizar
                </x-button>
            </div>
        </form>

    </x-wire-card>

    @push('js')
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <script>
            Dropzone.options.myDropzone = {
                // Configuration options go here
                addRemoveLinks: true,
                init: function(){
                    let myDropzone = this;
                    let images = @json($product->images);
                    //cargar imagen
                    images.forEach(function(image) {
                      let mockFile ={
                        id: image.id,
                        name: image.path.split('/').pop(),
                        sze: image.size,
                      }
                      //subir imagen
                      myDropzone.displayExistingFile(mockFile, `{{ Storage::url('${image.path}') }}`);
                      myDropzone.emit("complete", mockFile);
                      myDropzone.files.push(mockFile);
                    });

                    this.on("success", function(file, response){
                        file.id = response.id;
                    })

                    this.on("removedfile", function(file){


                        axios.delete(`/admin/images/${file.id}`)
                            .then(response => {
                                console.log(response.data);
                            })
                            .cath(error => {
                                console.error(error);
                            });
                    });
                }
            };
        </script>
    @endpush

</x-admin-layout>
