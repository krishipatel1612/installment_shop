@extends('layouts.admin')

@section('title','Edit Product')

@section('content')
<div class="container mt-4">

    <h2>Edit Product</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Category -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
        </div>

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $product->name) }}" required>
            @error('name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="price" class="form-label">Price (₹)</label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                   value="{{ old('price', $product->price) }}" step="0.01" required>
            @error('price') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
        </div>

        <!-- Interest Rate (EMI) -->
        <div class="mb-3">
            <label for="interest_rate" class="form-label">Interest Rate (%)</label>
            <input type="number" id="interest_rate" class="form-control" value="10" step="0.01">
        </div>

        <!-- Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
            @if($product->image)
                <img src="{{ asset('uploads/products/'.$product->image) }}" width="100" style="margin-top:10px;">
            @endif
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <!-- Live EMI Preview -->
        <div class="mb-3">
            <label class="form-label">EMI Preview</label>
            <div id="emiPreview" style="padding:10px; border:1px solid #ddd; border-radius:5px;">
                @foreach($product->installmentPlans as $plan)
                    <div>
                        {{ $plan->months }} months: Total ₹{{ $plan->total_amount }}, 
                        Monthly ₹{{ $plan->monthly_amount }}
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script>
const priceInput = document.getElementById('price');
const interestInput = document.getElementById('interest_rate');
const emiPreview = document.getElementById('emiPreview');
const monthsArr = [3,6,9,12];

function updateEMI(price, interest){
    let html = '';
    monthsArr.forEach(month => {
        let total = price + (price * interest / 100);
        let monthly = (total / month).toFixed(2);
        html += `<div>${month} months: Total ₹${total.toFixed(2)}, Monthly ₹${monthly}</div>`;
    });
    emiPreview.innerHTML = html;
}

// Initialize
updateEMI(parseFloat(priceInput.value), parseFloat(interestInput.value));

// Live update
priceInput.addEventListener('input', function(){
    updateEMI(parseFloat(priceInput.value) || 0, parseFloat(interestInput.value) || 0);
});
interestInput.addEventListener('input', function(){
    updateEMI(parseFloat(priceInput.value) || 0, parseFloat(interestInput.value) || 0);
});
</script>
@endsection
