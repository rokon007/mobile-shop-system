<?php

namespace App\Livewire\Filter;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Filter;
use App\Models\FilterOption;
use Livewire\Attributes\On;


class CategoryFilterComponent extends Component
{
    public $category;
    public $selectedFilters = [];
    public $products = [];
    public $category_id;
    public $filters = [];
    public $filter_name;
    public $is_active;
    public $updateButton=false;
    public $filterValueMood=false;
    public $filterMood=true;
    public $filterId;
    public $options;
    public $option_value;
    public $neWfilter_id;
    public $optionId;
    public $filterName;


    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->with('filters.options')->firstOrFail();
        $this->filterProducts();


        $category = Category::where('slug', $slug)->firstOrFail();
        $this->category_id = $category->id;
        $this->filters = Filter::where('category_id',$this->category_id)->get();

        // Load existing filters for the category
        $this->selectedFilters = $category->filters->pluck('id')->toArray();
    }

    public function filterProducts()
    {
        $query = Product::where('category_id', $this->category->id);

        foreach ($this->selectedFilters as $filterName => $filterValue) {
            $query->whereJsonContains("attributes->{$filterName}", $filterValue);
        }

        $this->products = $query->get();
    }
    public function editFilter($filter_id)
    {
        $filter=Filter::findOrFail($filter_id);
        $this->filter_name=$filter->name;
        $this->is_active=$filter->is_active;
        $this->filterId=$filter_id;
        $this->updateButton=true;
        $this->filterValueMood=false;
    }

    public function editOptionValue($option_id)
    {
        $option=FilterOption::findOrFail($option_id);
        $this->option_value=$option->value;
        $this->is_active=$option->is_active;
        $this->optionId=$option_id;
        $this->updateButton=true;
        $this->filterValueMood=true;
        $this->filterMood=false;
    }

    #[On('value-add')]
    public function addValue($filter_id)
    {
        $this->filterMood=false;
        $this->filterValueMood=true;
        $this->options=FilterOption::where('filter_id',$filter_id)->get();
        $this->neWfilter_id=$filter_id;
        $this->filterName=Filter::findOrFail($filter_id)->name;

    }
    public function saveValue()
    {
        $this->validate([
            'option_value' => 'required|string|max:255',
            'is_active' => 'required',
        ]);
        // Update or create the FilterOption
        FilterOption::updateOrCreate(
            ['id' => $this->optionId],
            [
                'value' => $this->option_value,
                'filter_id' => $this->neWfilter_id,
                'is_active' => $this->is_active,
            ]
        );
        $this->dispatch('value-add', filter_id: $this->neWfilter_id);
        session()->flash('message', 'Filter value updated successfully!');
        $this->resetValueForm();
        $this->updateButton=false;
    }

    public function saveFilters()
    {
        $this->validate([
            'filter_name' => 'required|string|max:255',
            'is_active' => 'required',
        ]);


        // Update or create the filter
        Filter::updateOrCreate(
            ['id' => $this->filterId],
            [
                'name' => $this->filter_name,
                'category_id' => $this->category_id,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('message', 'Filters updated successfully!');
        $this->resetForm();
        $this->updateButton=false;
    }
    public function resetForm()
    {
        $this->reset(['filter_name', 'is_active']);
    }
    public function resetValueForm()
    {
        $this->reset(['option_value', 'is_active']);
    }
    public function deleteFilter($filter_id)
    {
        $filter = Filter::findOrFail($filter_id);

        $filter->delete();

        session()->flash('message', 'Category deleted successfully.');
    }

    public function render()
    {
        return view('livewire.filter.category-filter-component', [
            'filters' => $this->filters,
        ])->layout('livewire.layout.app');
    }
}

