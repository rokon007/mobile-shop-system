<main>
    @section('title')
        <title>Admin | Inventory management</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
              integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endsection


    <main class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Inventory</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Product Inventory Management</li>
              </ol>
            </nav>
          </div>
          <div class="ms-auto">
            <div class="btn-group">
              <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
         </div>
        </div>
        <!--end breadcrumb-->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-4">
            <div class="col">
                <div class="card radius-10 bg-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                    <div class="">
                        <p class="mb-1 text-white">Product</p>
                        <h6 class="mb-0 text-white">{{$prouctData->name}}</h6>
                    </div>
                    <div class="ms-auto widget-icon bg-white-1 text-white">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            @php
                $category = $prouctData->category;

                if ($category) {
                    $catName = $category->parent_id
                                ? \App\Models\Category::find($category->parent_id)?->name
                                : $category->name;
                    $catImage=$category->parent_id
                                ? \App\Models\Category::find($category->parent_id)?->image
                                : $category->image;
                } else {
                    $catName = 'Unknown Category';
                }
            @endphp
           <div class="col">
            <div class="card radius-10 bg-success">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="">
                    <p class="mb-1 text-white">Category</p>
                    <h4 class="mb-0 text-white">{{$catName}}</h4>
                  </div>
                  <div class="ms-auto widget-icon bg-white-1 text-white">
                    @if ($catImage)
                        <img src="{{ Storage::url($catImage) }}" alt="Image" width="50" />
                    @else
                    <i class="bi bi-currency-dollar"></i>
                    @endif
                  </div>
                </div>
              </div>
            </div>
           </div>
           <div class="col">
            <div class="card radius-10 bg-pink">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="">
                    <p class="mb-1 text-white">Sub category</p>
                    <h4 class="mb-0 text-white">{{$prouctData->category->name}}</h4>
                  </div>
                  <div class="ms-auto widget-icon bg-white-1 text-white">
                    @if ($prouctData->category->image)
                        <img src="{{ Storage::url($prouctData->category->image) }}" alt="Image" width="50" />
                    @else
                    <i class="bi bi-currency-dollar"></i>
                    @endif
                  </div>
                </div>
              </div>
            </div>
           </div>
           <div class="col">
            <div class="card radius-10 bg-orange">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="">
                    <p class="mb-1 text-white">Brand</p>
                    <h4 class="mb-0 text-white">{{$prouctData->brand->name}}</h4>
                  </div>
                  <div class="ms-auto widget-icon bg-white-1 text-white">
                    @if ($prouctData->brand->logo)
                        <img src="{{ Storage::url($prouctData->brand->logo) }}" alt="Image" width="50" />
                    @else
                    <i class="bi bi-currency-dollar"></i>
                    @endif
                  </div>
                </div>
              </div>
            </div>
           </div>
        </div><!--end row-->


        @if (session()->has('message'))
            <div class="col-md-12 text-center">
                <center>
                    <div class="col-md-5">
                        <div class="alert border-0 bg-success alert-dismissible fade show py-2">
                            <div class="d-flex align-items-center">
                            <div class="fs-3 text-white"><i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-white">{{ session('message') }}</div>
                            </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </center>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex py-3">
              <h6 class="mb-0">Add Product Inventory</h6>
              <form class="d-flex ms-auto">
                <div class="input-group">
                    <input type="text" wire:model.live="search" class="form-control mt-2" placeholder="Search by SKU...">
                </div>
            </form>
            </div>
            <div class="card-body">
               <div class="row">
                 <div class="col-12 col-lg-4 d-flex">
                   <div class="card border shadow-none w-100">
                     <div class="card-body">
                        <form class="row g-3" wire:submit.prevent="{{ $selectedInventoryId ? 'updateInventory' : 'storeInventory' }}">
                         <div class="col-12 col-lg-12">
                           <label class="form-label">SKU</label>
                           <input type="text" wire:model='sku' class="form-control" placeholder="SKU">
                           @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
                         </div>
                         @if ($catId===1)
                             <div class="col-12 col-lg-12">
                                <label class="form-label">IMEI</label>
                                <input type="text" wire:model='imei' class="form-control" placeholder="IMEI">
                                @error('imei') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @else
                            <div class="col-12 col-lg-12">
                                <label class="form-label">Serial Number</label>
                                <input type="text" wire:model='serial_number' class="form-control" placeholder="serial_number">
                                @error('serial_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                         @endif
                         <div class="col-12 col-lg-6">
                          <label class="form-label">Purchase Price</label>
                          <input type="text" wire:model='purchase_price' class="form-control" placeholder="Purchase Price">
                          @error('purchase_price') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label">Selling price</label>
                            <input type="text" wire:model='selling_price' class="form-control" placeholder="Selling price">
                            @error('selling_price') <small class="text-danger">{{ $message }}</small> @enderror
                          </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label">Stock</label>
                            <input type="text" wire:model='quantity' class="form-control" placeholder="Stock" disabled>
                            @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
                          </div>
                          {{-- @foreach ($filtersData as $filter )
                            <div class="col-12 col-lg-6">
                                <label class="form-label">{{$filter->name}}</label>
                                <select wire:model='filter[]' class="form-select">
                                    @php
                                        $filterValues=App\Models\FilterOption::where('filter_id',$filter->id)->where('is_active',1)->get();
                                    @endphp
                                    <option value="">Select {{$filter->name}}</option>
                                    @forelse ($filterValues as $value)
                                        <option value="{{$value->id}}">{{$value->value}}</option>
                                    @empty
                                        <option>Value not set</option>
                                    @endforelse
                                </select>
                            </div>
                          @endforeach --}}
                            @foreach ($filtersData as $filter)
                                <div class="col-12 col-lg-6">
                                    <label class="form-label">{{ $filter->name }}</label>
                                    <select wire:model="filters.{{ $filter->id }}" class="form-select">
                                        <option value="">Select {{ $filter->name }}</option>
                                        @foreach ($filter->options as $option)
                                            <option value="{{ $option->id }}">{{ $option->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach




                        <div class="col-12">
                          <div class="d-grid">
                            <button class="btn btn-primary">
                                <span wire:loading.delay.long wire:target="{{ $selectedInventoryId ? 'updateInventory' : 'storeInventory' }}" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                {{ $selectedInventoryId ? 'Update Inventory' : 'Add Inventory' }}
                            </button>
                          </div>
                        </div>
                       </form>
                     </div>
                   </div>
                 </div>
                 <div class="col-12 col-lg-8 d-flex">
                  <div class="card border shadow-none w-100">
                    <div class="card-body">
                      <div class="table-responsive">
                         <table class="table align-middle">
                           <thead class="table-light">
                             <tr>
                               <th>SKU</th>
                               <th>IMEI</th>
                               <th>Purchase Price</th>
                               <th>Selling Price</th>
                               <th>Stock</th>
                               <th>Action</th>
                             </tr>
                           </thead>
                           <tbody>

                            @forelse ($inventories as $inventory)
                            <tr>
                                {{-- <td>
                                     @if ($inventory->images->isNotEmpty() && isset($inventory->images->first()->image_path))
                                        <img src="{{ Storage::url($inventory->images->first()->image_path) }}" alt="Image" width="50" />
                                     @endif
                                </td> --}}
                                <td>{{ $inventory->sku }}</td>
                                <td>{{ $inventory->imei }}</td>
                                <td>{{ $inventory->purchase_price}}</td>
                                <td>{{ $inventory->selling_price}}</td>
                                <td>{{ $inventory->quantity }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="editInventory({{ $inventory->id }})">Edit</button>
                                    <button class="btn btn-danger btn-sm" wire:click="deleteInventory({{ $inventory->id }})">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Inventory Found</td>
                            </tr>
                        @endforelse

                           </tbody>
                         </table>
                      </div>
                      <div class="mt-3">
                        {{ $inventories->links() }}
                    </div>
                    </div>
                  </div>
                </div>
               </div><!--end row-->
            </div>
        </div>














      </main>



    @section('JS')

    @endsection
</main>


