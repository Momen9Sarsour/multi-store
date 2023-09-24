@extends('adminStore.adminLayout')
@section('dashboard')
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

						<!--begin::Toolbar-->
						<div class="toolbar" id="kt_toolbar">
							<!--begin::Container-->
							<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
								<!--begin::Page title-->
								<!--end::Page title-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Toolbar-->
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
                            <form  action="{{ route('adminProduct.store') }}" method="post" enctype="multipart/form-data">
							  @csrf
                              <div class="form-group py-4">
                                <label for="">Product Name</label>
                                <input type="text" name="name" class="form-control" value="{{$product->name }}" placeholder="Enter the product name">
                              </div>
                              <div class="form-group py-4">
                                <label for="image">Image</label>
                                 <input type="file" name="image" class="form-control" placeholder="Enter the product image" />
                                 @if($product->image)
                                 <img src="{{asset('images/uploads/'.$product->image)}}" alt="" height="60">
                                 @endif
                               </div>
                               <div class="form-group py-4">
                                   <label for="">Store Parent</label>
                                   <select type="text" name="store_id" class="form-control form-select">
                                        {{-- <option value="">Primary Store</option> --}}
                                        @foreach($store as $store)
                                        <option value="{{$store->id}}">{{$store->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                               <div class="form-group py-4">
                                   <label for="">Category Parent</label>
                                   <select type="text" name="category_id" class="form-control form-select">
                                        {{-- <option value="">Primary Category</option> --}}
                                        @foreach($category as $category)
                                        <option value="{{$category->id}}">{{$category->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group py-4">
                                    <label for="">Description</label>
                                    <textarea class="form-control" name="description">{{$product->description}}</textarea>
                                </div>
                                <div class="form-group py-4">
                                    <label for="">Price</label> 
                                    <input type="number" name="price" class="form-control" value="{{$product->price }}">
                                </div>
                                <div class="form-group py-4">
                                    <label for="">Compare Price</label>
                                    <input type="number" class="form-control" name="compare_price" value="{{$product->compare_price}}">
                                </div>
                                <div class="form-group py-4">
                                    <label for="">Storge Quantity</label>
                                    <input type="number" class="form-control" name="storgeQuantity" value="{{$product->storgeQuantity}}">
                                </div>
                                <div class="form-group py-4">
                                  <label for="">Status</label>
                                     <div>
                                       <div class="form-check py-2">
                                         <input class="form-check-input" type="radio" name="status" value="active" checked @checked($product->status =='active')>
                                         <label class="form-check-label">Active</label>
                                       </div>
                                       <div class="form-check py-3">
                                         <input class="form-check-input" type="radio" name="status" value="archived" @checked($product->status =='archived')>
                                         <label class="form-check-label">Archived</label>
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
