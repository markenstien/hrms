<div class="card">
    <div class="card-body">
        <?php
        Form::open([
            'method' => 'get'
        ])
    ?>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group row">
                <?php
                    Form::label('Start Date');
                    Form::date('start_date','',null);
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group row">
                <?php
                    Form::label('End Date');
                    Form::date('end_date','',null);
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group row">
                <?php
                    Form::label('Display Option');
                    Form::select('display_option',['weekly_default' => 'Weekly Group','weekly_by_branch' => 'Group Result By Branch'],'',null);
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php
                    Form::submit('btn_filter', 'Apply Filter');

                    if(isset($_GET['btn_filter'])) {
                        ?> 
                            <a href="?" class="btn-danger btn">Remove Filter</a>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div>
        <?php
            $request = request()->inputs();
            $request['exportData'] = 'true';
            $exportLink = '?'.keypairtostr($request,'=','&', '','');

            echo wLinkDefault($exportLink, 'Export Result');
        ?>
    </div>
    <?php Form::close()?>
    </div>
</div>