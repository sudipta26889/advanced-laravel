<div class="footerArea">
  <div class="container">   
    
  </div><!--container ends-->
  

</div><!--footerArea ends-->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">var entity_id = "{{ session('entity_id') }}";</script>
<script type="text/javascript">var lang_id = "{{ session('lang_id') }}";</script>
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('website/js/bootstrap.min.js') }}"></script>
@yield('page_js')