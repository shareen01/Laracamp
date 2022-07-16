@extends('layouts.app')
@section('title')
    Store Homepage
@endsection
@section('content')
  <div class="page-content page-home">
      <section class="store-carousel">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <section class="home" id="home">        
                <div class="banner">
                    <h3>Happy Tongue</h3>
                    <p>High Quality Dessert with Affordable price.</p>
                    <a href="#" class="btn">get yours now</a>
                </div>
        
                <div class="swiper-pagination"></div>
        </section>
            </div>
          </div>
        </div>
      </section>
      <div class="container">
      <section class="about-section">
        
          <div class="inner-container">
            <h1>About Us</h1>
            <p class="text">
                 Berawal dari kecintaan terhadap dunia dessert, lahirlah happy tongue. Happy tongue berdiri bulan Juni 2022. Kami selalu berusaha memberikan yang terbaik untuk pelanggan.
            </p>
            <div class="skills">
                <span>Enak</span>
                <span>Harga Affordable</span>
                <span>Higienis</span>
            </div>
        </div>
    </section>
  </div>
      <section class="store-trend-categories">
        <div class="container">
          <div class="row">
            <div class="col-12" data-aos="fade-up">
              <h5>Trend Categories</h5>
            </div>
          </div>
          <div class="row">
            @php
              $incrementCategory = 0 
            @endphp
            @forelse ( $categories as $category)
            <div
              class="col-6 col-md-3 col-lg-2"
              data-aos="fade-up"
              data-aos-delay="{{ $incrementCategory*= 100 }}"
            >
              <a href="{{ route('categories-detail',$category->slug) }}" class="component-categories d-block">
                <div class="categories-image">
                  <img src="{{ Storage::url($category->photo) }}" class="w-100" />
                </div>
                <p class="categories_text">{{$category->name}}</p>
              </a>
            </div>
            @empty
            <div class="col-12 text-center py-5" data-aos="fade-up"
            data-aos-delay="100">
            No Categories Found
            </div>
            @endforelse
    
       
          </div>
        </div>
      </section>
      <section class="store-new-products">
        <div class="container">
          <div class="row">
            <div class="col-12" data-aos="fade-up">
              <h5>New Products</h5>
            </div>
          </div>
          <div class="row">
            @php
              $incrementProduct = 0
            @endphp
            @forelse ($products as $product)
            <div
            class="col-6 col-md-4 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="{{ $incrementProduct *= 100 }}"
          >
          <div class="box">
            <a href="{{ route('detail',$product->slug) }}" class="component-products d-block">
              <div class="products-thumbnail">
                <div
                  class="products-image"
                  style="
                    @if($product->galleries->count())
                      background-image: url('{{ Storage::url($product->galleries->first()->photos) }}')
                    @else
                      background-color: #eee
                    @endif
                  "
                > 
              </div>
              </div>
              <div class="products-text">{{ $product->name }}</div>
              <div class="products-price">${{ $product->price }}</div>
              <a href="#" class="btnn">add to cart</a>
            </a>
          </div>
          </div>
            @empty
          <div class="col-6 col-md-4 col-lg-3"
          data-aos="fade-up"
          data-aos-delay="100">No Products Found</div>
            @endforelse
          </div>
        </div>
      </section>
    </div>
@endsection
