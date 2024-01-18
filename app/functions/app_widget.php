<?php  

    function wControlButtonRight($moduleName, $links = []) {
        $linkString = '';

        foreach($links as $key => $row) {
            $icon = $row['attributes']['icon'] ?? 'fas fa-plus-circle';
            $linkAttributes = '';
            
            if(!empty($row['attributes']['link-attributes'])) {
                $linkAttributes .= keypair_to_str($row['attributes']['link-attributes']);
            }
            
            $linkString .= <<<EOF
                <a href="{$row['url']}" class="btn btn-primary btn-sm bg-gradient-primary rounded-0 btn-icon-split mb-0" {$linkAttributes}>
                    <span class="icon text-white-600">
                        <i class="{$icon}"></i>
                    </span>
                    <span class="text">{$row['label']}</span>
                </a>
            EOF;
        }
        
        return <<<EOF
            <div class="d-flex w-100 align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <h1 class="h3 mb-0 text-gray-800">{$moduleName}</h1>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right">
                    {$linkString}
                </div>
            </div>
            <hr/>
        EOF;
    }

    function wControlButtonLeft($moduleName, $links = []) {
        $linkString = '';
        foreach($links as $key => $row) {

            $icon = $row['attributes']['icon'] ?? 'fas fa-chevron-left';
            $linkString .= <<<EOF
                <a href="{$row['url']}" 
                    class="btn btn-light bg-gradient-light border rounded-0 btn-icon-split mb-4">
                    <span class="icon text-white">
                        <i class="{$icon}"></i>
                    </span>
                    <span class="text">{$row['label']}</span>
                </a>
            EOF;
        }
        return <<<EOF
            <div class="d-flex w-100 align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <h1 class="h3 mb-0 text-gray-800">{$moduleName}</h1>
                    {$linkString}
                </div>
            </div>
        EOF;
    }

    function wCardTitle($title) {
        return <<<EOF
            <h6 class='m-0 font-weight-bold'>{$title}</h6>
        EOF;
    }

    function wCardHeader($content) {
        return <<<EOF
            <div class="card-header py-3" style="background-color:#0D0CB5;
                color:#fff">
                {$content}
            </div>
        EOF;
    }


    function wReturnLink( $route )
    {
        print <<<EOF
            <a href="{$route}">
                <i class="feather icon-corner-up-left"></i> Return
            </a>
        EOF;
    }

    function wLinkDefault($link , $text = 'Edit' , $attributes = [])
	{	
		$icon = isset($attributes['icon']) ? "<i class='{$attributes['icon']}'> </i>" : null;
		$attributes = is_null($attributes) ? $attributes : keypairtostr($attributes);
		return <<<EOF
			<a href="{$link}" style="text-decoration:underline" {$attributes}>{$icon} {$text}</a>
		EOF;
	}

    function wWrapSpan($text)
    {
        $retVal = '';
        
        if(is_array($text))
        {
            foreach($text as $key => $t) 
            {
                if( $key > 3 )
                    $classHide = '';
                $retVal .= "<span class='badge badge-primary badge-classic'> {$t} </span>";
            }
        }else{
            $retVal = "<span class='badge badge-primary badge-classic'> {$text} </span>";
        }

        return $retVal;
    }

    function wBadgeWrap($text, $type) {
        return <<<EOF
            <span class='badge bg-{$type}'>{$text}</span>
        EOF;
    }

    function wDivider($height = 30)
    {
        return <<<EOF
            <div style="margin-top:{$height}px"> </div>
        EOF;
    }