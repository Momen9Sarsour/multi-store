@extends('employeeVendor.layoutemployeeVendor')
@section('dashboard')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('employeeVendor/order/update', $order->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="form-group py-4">
                        <label for="">User Name</label>
                        <select type="text" name="user_id" class="form-control form-select">
                            @foreach ($user as $user)
                                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Store Parent</label>
                        <select type="text" name="store_id" class="form-control form-select">
                            @foreach ($store as $store)
                                <option value="{{ $store->id }}" {{ $order->store_id == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Delivery Name</label>
                        <select type="text" name="delivery_id" class="form-control form-select">
                            @foreach ($delivery as $delivery)
                                <option value="{{ $delivery->id }}"
                                    {{ $order->delivery_id == $delivery->id ? 'selected' : '' }}>
                                    {{ $delivery->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Product Name</label>
                        <select type="text" name="product_id" class="form-control form-select">
                            @foreach ($product as $product)
                                <option value="{{ $product->id }}"
                                    {{ $order->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group py-4">
                        <label for="">Price</label>
                        <input type="number" name="total_price" class="form-control" value="{{ $order->price_total }}">
                    </div>
                    <div class="form-group py-4">
                        <label for="">Address Order</label>
                        <input type="string" name="address" class="form-control" value="{{ $order->address }}">
                    </div>
                    <div class="form-group py-4">
                        <label for="">Status</label>
                        <div>
                            <div class="form-check py-2">
                                <input class="form-check-input" type="radio" name="status" value="pending" checked
                                    @checked($order->status == 'pending')>
                                <label class="form-check-label">Pending</label>
                            </div>
                            <div class="form-check py-3">
                                <input class="form-check-input" type="radio" name="status" value="delivered"
                                    @checked($order->status == 'delivered')>
                                <label class="form-check-label">Delivered</label>
                            </div>
                            <div class="form-check py-3">
                                <input class="form-check-input" type="radio" name="status" value="accepted"
                                    @checked($order->status == 'accepted')>
                                <label class="form-check-label">Accepted</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>
            </div>

            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
@endsection