<div class="ereaders-fancy-title">
    <h2>{{$title}}</h2>
    <div class="clearfix"></div>
    <p>{{$description}}</p>
    <div class="clearfix"></div>
    @isset($action_text)
    <a  class="ereaders-color" href="{{ $action_link }}" >{{$action_text}}</a>
    @endisset
</div>

