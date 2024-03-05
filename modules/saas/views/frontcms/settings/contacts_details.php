<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        <h4 class="modal-title"><?= _l('contacts_details') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="panel-body form-horizontal">
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= _l('name') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php if (!empty($details->name)) echo html_escape($details->name); ?></p>
                </div>
            </div>
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= _l('phone') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php if (!empty($details->phone)) echo html_escape($details->phone); ?></p>
                </div>
            </div>
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= _l('email') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php if (!empty($details->email)) echo html_escape($details->email); ?></p>
                </div>
            </div>
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= _l('subject') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php if (!empty($details->subject)) echo html_escape($details->subject); ?></p>
                </div>
            </div>
            
            <div class="col-md-12 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="control-label"><strong><?= _l('description') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <blockquote><?php echo $details->description; ?></blockquote>
                </div>
            </div>
        
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
    </div>
</div>






