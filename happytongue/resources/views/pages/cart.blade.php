@extends('layouts.app')

@section('title')
    Store Cart Page
@endsection

@section('content')
    <!-- page content -->
    <div class="page-content page-cart">
      <section
        class="store-breadcrumbs"
        data-aos="fade-down"
        data-aos-delay="100"
      >
        <div class="container">
          <div class="row">
            <div class="col-12">
              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="">Home</a>
                  </li>
                  <li class="breadcrumb-item active">Cart</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>
              <div class="cart">

                <div class="products">
                  @php $totalPrice = 0 @endphp

                  @foreach ($carts as $cart)
                    <div class="product">
                         @if ($cart->product->galleries)
                        <img class= "foto" src="{{ Storage::url($cart->product->galleries->first()->photos) }}">
                        @endif
                        <div class="product-info">
            
                            <h3 class="product-name">{{ $cart->product->name }}</h3>
            
            
                            <p class="product-harga">${{ number_format($cart->product->price) }}</p>
            
                            <p class="product-quantity">Qnt: <input value="1" name="">
                              <form action="{{ route('cart-delete', $cart->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                  <button type="submit" class="btn tombol btn-remove-cart">Remove</button>
                              </form>
        
            
                        </div>
            
                    </div>
                    @php $totalPrice += $cart->product->price @endphp
                    @endforeach
                </div>
            
                <div class="cart-total">
            
                    <p>
            
                        <span>Total Price</span>
            
                        <span>>${{ number_format($totalPrice ?? 0) }}</span>
            
                    </p>
            
                    <p>
                        <span>Number of Items</span>
            
                        <span>2</span>
            
                    </p>
            
            
                </div>
            
            </div>
            <section class="store-cart">
              <div class="container">
                <div class="row" data-aos="fade-up" data-aos-delay="150">
                  <div class="col-12">
                    <hr />
                  </div>
                  <div class="col-12">
                    <h2 class="mb-4">Shipping Details</h2>
                  </div>
                </div>
      
                <form action="{{ route('checkout') }}" id="locations" enctype="multipart/form-data" method="POST">
                  @csrf
                  <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                  <div class="row mb-2" data-aos="fade-up" data-aos-delay="200">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="address_one">Address 1</label>
                      <input
                        type="text"
                        class="form-control"
                        id="address_one"
                        name="address_one"
                        value="Setra Duta Cemara"
                      />
                    </div>
                  </div>
      
            
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="zip_code">Postal Code</label>
                      <input
                        type="text"
                        class="form-control"
                        id="zip_code"
                        name="zip_code"
                        value="40512"
                      />
                    </div>
                  </div>
  
      
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="phone_number">Mobile</label>
                      <input
                        type="text"
                        class="form-control"
                        id="phone_number"
                        name="phone_number"
                        value="+628 2020 11111"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="gmail">Gmail</label>
                      <input type="email" placeholder="enter your gmail">
                      />
                    </div>
                  </div>
                </div>
      
                <div class="row" data-aos="fade-up" data-aos-delay="200">
                  <div class="col-12">
                    <button
                      type="submit"
                      class="btn btn-success mt-4 px-4 btn-block"
                      >Checkout Now
                    </button>
                  </div>
                </div>
                </form>
              </div>
            </section>
            </div>
          
@endsection

@push('addon-script')
    <script src="/vendor/vue/vue.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
      var locations = new Vue({
        el: "#locations",
        mounted() {
          AOS.init();
          this.getProvincesData();
        },
        data: {
          provinces: null,
          regencies: null,
          provinces_id: null,
          regencies_id: null,
        },
        methods: {
          getProvincesData(){
            var self = this;
            axios.get('{{ route('api-provinces') }}')
            .then(function(response){
              self.provinces = response.data;
            })
          },
          getRegenciesData(){
            var self = this;
            axios.get('{{ url('api/regencies') }}/' + self.provinces_id)
            .then(function(response){
              self.regencies = response.data;
            })
          },
        },
      watch:{
        provinces_id: function(val, oldVal){
          this.regencies_id = null;
          this.getRegenciesData();
        }
      }
      });
    </script>
@endpush

