<div class="message">

    <p>Olá, {{$user->name}}.</p><br>
    Obrigado por criar uma nova conta :) <br><br> 
    
    Por favor, valide sua conta <a href="{{$link}}" class="btn btn-info">{{ __('Clicando aqui') }}</a>
    <br>
    <p>Atenciosamente, equipe {{get_option('app_name')}}</p>
</div>
