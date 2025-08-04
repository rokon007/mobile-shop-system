<main>
    @section('title')
        <title>Admin | Filters for {{ $category->name }}</title>
    @endsection
    @section('css')

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
              integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endsection

    <main class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Product Management</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Filters for {{ $category->name }}</li>
              </ol>
            </nav>
          </div>
          <div class="ms-auto">
            <div class="btn-group">
              <button type="button" class="btn btn-primary">Settings</button>
              <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <!--end breadcrumb-->
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
        @if ($filterMood)
            <div class="card">
                <div class="card-header py-3">
                <h6 class="mb-0">Add Filters for {{ $category->name }}</h6>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-4 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                        <form class="row g-3" wire:submit.prevent="saveFilters">
                            <div class="col-12">
                            <label for="name" class="form-label">Filters Name </label>
                            <input class="form-control" type="text" wire:model="filter_name" placeholder="Filters Name">
                            @error('filter_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                                <label for="name" class="form-label">Is active</label>
                                <select class="form-control" wire:model="is_active">
                                    <option value="">Set status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.delay.long wire:target="saveFilters" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    @if($updateButton)
                                        Update Filter
                                    @else
                                        Add Filter
                                    @endif
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
                                <th>Id</th>
                                <th>Filter Name</th>

                                <th>Is active</th>
                                <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($category->filters as $filter)
                                <tr>
                                    <td>{{ $filter->id }}</td>
                                    <td>{{ $filter->name }}</td>

                                {{-- <td>{!! $option->value ?? '<span class="badge rounded-pill bg-dark">N/A</span>' !!}</td> --}}
                                    @if ($filter->is_active===1)
                                        <td><span class="badge rounded-pill bg-success">Active</span></td>
                                    @else
                                        <td><span class="badge rounded-pill bg-danger">Inactive</span></td>
                                    @endif
                                <td>
                                    <div class="d-flex align-items-center gap-3 fs-6">
                                        <a style="cursor: pointer;"  wire:click="addValue({{ $filter->id }})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Add value" aria-label="Add value"><span class="badge bg-warning text-dark">Add value</span></a>
                                    <a style="cursor: pointer;"  wire:click="editFilter({{ $filter->id }})" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Edit"><i class="bi bi-pencil-fill"></i></a>
                                    <a style="cursor: pointer;"  wire:click="deleteFilter({{ $filter->id }})" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="bi bi-trash-fill"></i></a>
                                    </div>
                                </td>
                                </tr>
                                @empty
                                <p>No filter found.</p>
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    </div>
                </div><!--end row-->
                </div>
            </div>
        @elseif($filterValueMood)
            <div class="card">
                <div class="card-header py-3">
                <h6 class="mb-0">Add Value of {{$filterName}} Filter for {{ $category->name }} category</h6>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-4 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                        <form class="row g-3" wire:submit.prevent="saveValue">
                            <div class="col-12">
                            <label for="name" class="form-label">Value </label>
                            <input class="form-control" type="text" wire:model="option_value" placeholder="Value">
                            @error('option_value') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                                <label for="name" class="form-label">Is active</label>
                                <select class="form-control" wire:model="is_active">
                                    <option value="">Set status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.delay.long wire:target="saveValue" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    @if($updateButton)
                                        Update Value
                                    @else
                                        Add Value
                                    @endif
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
                                <th>Id</th>
                                <th>Value</th>
                                <th>Is active</th>
                                <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($options as $option)
                                <tr>
                                    <td>{{ $option->id }}</td>
                                    <td>{{ $option->value }}</td>

                                {{-- <td>{!! $option->value ?? '<span class="badge rounded-pill bg-dark">N/A</span>' !!}</td> --}}
                                    @if ($option->is_active===1)
                                        <td><span class="badge rounded-pill bg-success">Active</span></td>
                                    @else
                                        <td><span class="badge rounded-pill bg-danger">Inactive</span></td>
                                    @endif
                                <td>
                                    <div class="d-flex align-items-center gap-3 fs-6">
                                    <a style="cursor: pointer;"  wire:click="editOptionValue({{ $option->id }})" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Edit"><i class="bi bi-pencil-fill"></i></a>
                                    <a style="cursor: pointer;"  wire:click="deleteOptionValue({{ $option->id }})" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="bi bi-trash-fill"></i></a>
                                    </div>
                                </td>
                                </tr>
                                @empty
                                <p>No filter found.</p>
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    </div>
                </div><!--end row-->
                </div>
            </div>
        @endif


    </main>


    <main class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Admin</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Brands</li>
              </ol>
            </nav>
          </div>
          <div class="ms-auto">
            <div class="btn-group">
              <button type="button" class="btn btn-primary">Settings</button>
              <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <!--end breadcrumb-->
        <div>
            <h1>Filters for {{ $category->name }}</h1>

            @foreach($category->filters as $filter)
                <div class="filter-group">
                    <h4>{{ $filter->name }}</h4>
                    @foreach($filter->options as $option)
                        <label>
                            <input type="checkbox"
                                wire:model="selectedFilters.{{ $filter->name }}"
                                value="{{ $option->value }}">
                            {{ $option->value }}
                        </label>
                    @endforeach
                </div>
            @endforeach

            <h2>Filtered Products</h2>
            <div class="product-list">
                @forelse($products as $product)
                    <div class="product-item">
                        <h4>{{ $product->name }}</h4>
                        <p>Price: {{ $product->price }}</p>
                    </div>
                @empty
                    <p>No products found.</p>
                @endforelse
            </div>
        </div>


        <div>
            <h2>Manage Filters for Category</h2>

            <form wire:submit.prevent="saveFilters">
                <div class="mb-3">
                    <label for="filters">Select Filters:</label>
                    <div>
                        <input type="text" class="from-control" wire:model='filter_name'>
                        {{-- @foreach($filters as $filter)
                            <div>
                                <input type="checkbox" id="filter_{{ $filter->id }}"
                                       wire:model="selectedFilters" value="{{ $filter->id }}">
                                <label for="filter_{{ $filter->id }}">{{ $filter->name }}</label>
                            </div>
                        @endforeach --}}
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Filters</button>
            </form>

            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
        </div>

    </main>


    @section('JS')

@endsection
</main>
