<div >
    @php
		if(!empty($_REQUEST['buscar'])){ $doc = $_REQUEST['buscar'];
		}else{ $doc = ''; }
	@endphp
	<input type="text" name="buscar" value="{{$doc}}" placeholder="Buscar por documento y/o medidor" class="form-control">
</div>
