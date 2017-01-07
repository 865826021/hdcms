<div class="template clearfix">
    <foreach from="$data" value="$d">
        <div class="thumbnail">
            <h5>{{$d['title']}}</h5>
            <img src="theme/{{$d['name']}}/{{$d['thumb']}}">
            <div class="caption">
                <button type="button" class="btn btn-default btn-xs btn-block" id="{{$d['tid']}}" thumb="{{$d['thumb']}}" title="{{$d['title']}}" name="{{$d['name']}}" type="button">选择模板</button>
            </div>
        </div>
    </foreach>
</div>

<style>
    .template .panel-heading {
        padding : 0px;
    }

    .template .navbar-default {
        margin-bottom : 0px;
        border        : none;
    }

    .template .thumbnail {
        height   : 300px;
        width    : 180px;
        overflow : hidden;
        float    : left;
        margin   : 3px 7px;
    }

    .template .thumbnail .caption {
        padding : 0px;
    }

    .template .thumbnail h5 {
        font-size   : 14px;
        overflow    : hidden;
        height      : 25px;
        margin      : 3px 0px;
        line-height : 2em;
    }

    .template .thumbnail > img {
        height        : 225px;
        max-width     : 168px;
        border-radius : 3px;
    }

    .template .thumbnail .caption {
        margin-top : 8px;
    }
</style>