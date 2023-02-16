<div class="direction-task">
    <hr class="mb-8">
    </br>
    <hr class="mb-8">  <!-- просто рисочка -->
    <div class=".col-md-4 .col-md-4"> <!-- блок чекбоксів перевизначення специфіки робіт -->
        <div class="row">
            @foreach($workspecs as $workspec)
                &nbsp
                <div class="form-check form-check-inline .col-md-1">
                    <input class="form-check-input works_specs_id" type="radio"
                           @if  ($workspec['id'] == $worksSpecsId)
                               {{'checked=true'}}
                           @endif
                           wire:click="directDialer('{{$workspec['id']}}')" name="directions"
                           id="dir{{$workspec['id']}}" value="{{$workspec['id']}}">
                    <!-- в контролер DirectionTas передається id перевизначеної специфіки робіт -->
                    <!-- в функцію choose_direction -->
                    <label class="form-check-label" for="inlineRadio{{$workspec['id']}}">{{$workspec['body']}}</label>
                </div>
            @endforeach
        </div>
    </div>  <!--кінець  блоку  чекбоксів перевизначення специфіки робіт -->
    <hr class="mb-8">
    <div class="row">
        <div class="col-md-3">
            <i style="font-size: 10pt; margin-top:5pt;">Доручається на: </i>
        </div>
        <div class="col-md-6 col-lg-9">
            <div class="input-group flex-nowrap">

                <div class="input-group-prepend">
                    <span class="input-group-text">підстанції</span>
                    <select class="form-control" id="choose_substation" name="choose_substation"
                            OnChange=GetSelectedSubstation()
                            wire:model="substationDialer" required>
                        @foreach($substations as $substation)
                            <option @if ($substation['id'] === $orderRecord->substationId)
                                        {{' selected=true '}}
                                    @endif
                                    VALUE="{{$substation['id']}}"
                            > {{$substation['body']}}</option>
                        @endforeach
                    </select>
                </div>
                &nbsp
                &nbsp
                <div class="input-group-prepend">
                    <span class="input-group-text">ПЛ</span>
                    <select class="form-control" id="sel_line_list" name="sel_line_list" OnChange=GetSelectedLine()
                            required> <!--   Перелік ліній завантажуємо у випадаючий список       -->
                        @foreach($lines as $line)
                            <option
                                @if ($line['line_id'] === $orderRecord->lineId))
                                    {{' selected=true '}}
                                @endif
                                VALUE="{{$line['line_id']}}">
                                {{$line['line_id']}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3">
            <label><i style="font-size: 10pt;">виконати</i></label>
            <div class="input-group">
                <select class="form-control" id="sel_w_list" OnChange=GetSelectedTask() required>
                    <!--   Перелік можливих завдань завантажуємо у випадаючий список       -->
                    @foreach($taskslist as $task)
                        <option VALUE="{{$task['id']}}"> {{$task['body']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-lg-9">
            <textarea id="workslist" name="workslist" class="form-control" rows="5" style="text-align:left;">
                @if ($mode!=='create')
                    {{$workslist}}
                @endif
            </textarea>
        </div>
    </div>
</div>
