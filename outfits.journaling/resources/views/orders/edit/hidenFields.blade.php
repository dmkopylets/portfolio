<input type="hidden" name="order_id"               id="order_id"              value="{{$orderRecord->id}}">
<input type="hidden" name="mode"                   id="mode"                  value="{{$orderRecord->editMode}}">
<input type="hidden" name="write_to_db_brigade"    id="write_to_db_brigade"   value="{{$orderRecord->brigadeMembersIds}}">
<input type="hidden" name="write_to_db_engineers"  id="write_to_db_engineers" value="{{$orderRecord->brigadeEngineerIds}}">
