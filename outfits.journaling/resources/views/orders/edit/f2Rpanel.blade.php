<div class="col-md-3 order-md-2 mb-3">
    <h4 class="d-flex justify-content-between align-items-lg-center mb-3">
        <span class="text-success">Бригада</span>
        @if ($mode!=='create')
            <span class="badge badge-secondary badge-pill" id="countbrigade">{{$countbrigade}}</span>
        @else
            <span class="badge badge-secondary badge-pill" id="countbrigade">0</span>
        @endif
    </h4>
    <ul class="list-group mb-3" style="padding-left:20px">
        @foreach($brig_m_arr as $br_member)
            <li class="list-group-item-brmem d-flex justify-content-between lh-condensed">
                <div>
                    @if ($mode!=='create')
                    <input class="form-check-input" type="checkbox" style="padding-left:10px" <?php if ( in_array($br_member->id,explode(",",$naryadRecord['brigade_m']))) {echo ' checked ';} ?>  id="brigade_member{{$br_member->id}}" onclick="brig_engr_checked()">
                    @else
                        <input class="form-check-input" type="checkbox" style="padding-left:10px"   id="brigade_member{{$br_member->id}}" onclick="brig_engr_checked()">
                    @endif
                    <label class="form-check-label" style="font-size: 12px;padding-left:10px" for="brigade_member{{$br_member->id}}">{{$br_member->body}}</label>
                </div>
            </li>
        @endforeach
    <!--  машиністи-стропальщики  -->
        @foreach($brig_e_arr as $engineer)
            <li class="list-group-item-enineers d-flex justify-content-between lh-condensed">
                <div>
                    @if ($mode!=='create')
                         <input class="form-check-input" type="checkbox"  <?php if ( in_array($engineer->id,explode(",",$naryadRecord['brigade_m']))) {echo ' checked ';} ?>  id="engineer{{$engineer->id}}" onclick="brig_engr_checked()">
                    @else
                         <input class="form-check-input" type="checkbox"   id="engineer{{$engineer->id}}" onclick="brig_engr_checked()">
                    @endif
                            <label class="form-check-label" style="font-size: 12px; color: #ef7f1a" for="engineer{{$engineer->id}}">{{$engineer->body.' '.$engineer->specialization}}</label>
                </div>
            </li>
        @endforeach
    </ul>
</div>

