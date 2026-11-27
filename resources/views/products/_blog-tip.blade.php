{{-- Tip z blogu pod popisem produktu (článek vybírá App\Helpers\BlogTipHelper podle config/blog_tips.php) --}}
<p class="text-xs uppercase tracking-widest text-warm-500 leading-5">
  {{ __('products.show.blog_tip') }}
  <a href="{{ localizedRoute('blog.show', ['post' => $blogTip->getSlug()]) }}" class="group text-dark-800 hover:text-primary-500 transition-colors">{{ $blogTip->getTitle() }} <span class="inline-block text-primary-500 group-hover:translate-x-1 transition-transform">&rarr;</span></a>
</p>
