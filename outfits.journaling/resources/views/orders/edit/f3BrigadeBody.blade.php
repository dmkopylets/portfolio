
    <div class="row">
        <div class="col-md-3">
            <p class="lead" style="font-size: 10pt;">Керівнику робіт (наглядачу)</p>
        </div>
        <div class="col-md-6 col-lg-9">
            <select class="custom-select d-block w-100" id="warden" name="warden" required>
                @foreach($wardens as $warden)
                    @if ($mode!=='create')
                      <option  <?php  if ($warden->id == $naryadRecord['warden_id']) {echo ' selected ';} ?> VALUE="{{$warden->id}}"> {{$warden->body.', '.$warden->group}}</option>
                    @else
                      <option VALUE="{{$warden->id}}">{{$warden->body.', '.$warden->group}}</option>
                    @endif
                @endforeach
            </select>
            <span class="text-muted"><i>(прізвище, ініціали, група з електробезпеки)</i></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <p class="lead" style="font-size: 10pt;">допускачу</p>
        </div>
        <div class="col-md-6 col-lg-9">
            <select class="custom-select d-block w-100" id="adjuster" name="adjuster" required>
                @foreach($adjusters as $adjuster)
                  @if ($mode!=='create')
                    <option <?php if ($adjuster->id == $naryadRecord['adjuster_id']) {echo ' selected ';} ?> VALUE="{{$adjuster->id}}">{{$adjuster->body.', '.$adjuster->group}}</option>
                  @else
                    <option VALUE="{{$adjuster->id}}">{{$adjuster->body.', '.$adjuster->group}}</option>
                  @endif
                @endforeach
            </select>
            <span class="text-muted"><i>(прізвище, ініціали, група з електробезпеки)</i></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label for="brigade_members"  style="font-size: 10pt;">з членами бригади:</label>
        </div>
              @if ($mode!=='create')
                <?php $brlist=''; 
                if (isset($array['$brigade_txt'])) { 
                        foreach($brigade_txt as $txt1) 
                            {
                              $brlist=$brlist.$txt1->body.' '.$txt1->group.', ';
                            }
                        } 
                if (isset($array['$engineers_txt'])) { 
                        foreach($engineers_txt as $txt2) 
                            {
                              $brlist=$brlist.$txt2->specialization.' '.$txt2->body.' '.$txt2->group.', ';
                            }
                        }                
                ?>                   
              @endif
        <div class="col-md-6 col-lg-9">
            <textarea class="form-control" id="brigade_members" name="brigade_members" rows="5"> @if ($mode!=='create') {{trim($brlist)}} @endif </textarea>
            <span class="text-muted"><i>(прізвище, ініціали, група з електробезпеки)</i></span>
        </div>
    </div>



