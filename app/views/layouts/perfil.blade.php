<div class="ui segment">
  <h3 class="ui top attached header">
    {{Session::get("lang.home_perfil")}}
  </h3>
  <div class="ui card fluid" style="text-align:center;">
    <div class="content">
      @if(Auth::user()->gender_id == "1")
        <img src="/assets/img/woman.png" style="margin:1em;">
      @else 
        <img src="/assets/img/men.png" style="margin:1em;">
      @endif
      <a class="header">{{Auth::user()->last_name}} {{Auth::user()->name}}</a>
        <div class="meta">
          <span class="date">{{Auth::user()->originCountry->nombre}}</span>
        </div>
        <div class="description">
          {{Auth::user()->email}}
        </div>
    </div>
      @if(Auth::user()->profile_id == "1")
      <a href="/" class="redMenu">
        {{Session::get("lang.home_mis_ofertas")}}
      </a>
      <a href="/ofertas/" class="redMenu">
        {{Session::get("lang.home_ver_ofertas")}}
      </a>
      @endif
      <a href="/logout/" class="redMenu">
        {{Session::get("lang.home_cerrar_session")}}
      </a>
    
  </div>
</div>