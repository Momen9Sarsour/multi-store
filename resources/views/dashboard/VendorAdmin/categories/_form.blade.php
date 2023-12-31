@if ($errors->any())
    <div class="alert alert-danger">
        <h3>Error Occured</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group py-4">
    <x-form.input label="Category Name" class="form-control-lg" role="input" name="name" :value="$category->name" />
</div>

<div class="form-group">
    <label for="">Category Parent</label>
    <select type="text" name="parent_id" class="form-control form-select">
        <option value="">Primary Category </option>
        @foreach ($parents as $parent)
            <option
                value="{{ $parent->id }}"{{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                {{ $parent->name }} </option>
        @endforeach
    </select>
</div>

<div class="form-group py-4">
    <x-form.textarea label="Description" name="description" :value="$category->description" />
</div>

<div class="form-group">
    <x-form.label id="image">Image</x-form.label>
    <x-form.input type="file" name="image" class="form-control" accept="image/*" />
    @if ($category->image)
        <img src="{{ asset('storage/' . $category->image) }}" alt="" height="60">
    @endif
</div>

<div class="form-group py-4">
    <label for="">Status</label>
    <div>
        <x-form.radio name="status" :checked="$category->status" :options="['active' => 'Active', 'archived' => 'Archived']"></x-form.radio>
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">Save</button>
</div>
