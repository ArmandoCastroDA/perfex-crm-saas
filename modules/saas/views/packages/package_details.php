<div class="panel panel-custom package_modal">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= _l('package_details') . ' - ' . $package_info->name ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap package_details">
        <section class="package-section">
            <div class='packaging packaging-palden'>
                <div class='packaging-item'>
                    <div class='packaging-deco custom-bg'>
                        <svg class='packaging-deco-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1'
                             preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100' width='300px' x='0px'
                             xml:space='preserve'
                             xmlns='http://www.w3.org/2000/svg'
                             y='0px'>
          <path class='deco-layer deco-layer--1'
                d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z'
                fill='#FFFFFF' opacity='0.6'></path>
                            <path class='deco-layer deco-layer--2'
                                  d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z'
                                  fill='#FFFFFF' opacity='0.6'></path>
                            <path class='deco-layer deco-layer--3'
                                  d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;H42.401L43.415,98.342z'
                                  fill='#FFFFFF' opacity='0.7'></path>
                            <path class='deco-layer deco-layer--4'
                                  d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z'
                                  fill='#FFFFFF'></path>
</svg>
                        <div class='packaging-package'><span
                                    class='packaging-currency'> </span><?= $package_info->name ?>
                            <span class='packaging-period'></span>
                        </div>

                        <div class="package_position <?= !empty($package_info->frequency) ? 'jcenter' : '' ?>">
                            <?php
                            if (!empty($package_info->frequency)) { ?>
                                <h3 class="packaging-title text-center"> <?= display_money($package_info->amount, default_currency()) . ' / ' . _l($package_info->frequency) ?></h3>
                            <?php } else {
                                echo package_price($package_info);
                            }
                            ?>
                        </div>


                    </div>
                    <ul class='packaging-feature-list'>
                        <?= saas_packege_list($package_info) ?>
                    </ul>

                </div>

            </div>
    </div>
    </section>
</div>
<div class="modal-footer">
    <a href="#" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></a>
</div>
</div>
