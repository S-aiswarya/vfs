<div class="custom-file mb-4">
    <input type="file" class="custom-file-input image-upload" id="{{$id}}" name="{{$name}}">
    <label class="custom-file-label" for="{{$id}}">Choose file</label>
</div>
<div class="file-upload-holder" @if(!$image) style="display: none;" @endif>
    <img @if($image) src="{{BladeHelper::asset($image)}}" @else src="" @endif>
    <a href="javascript:void(0);" class="text-danger remove-uploaded-image"><i class="fas fa-window-close"></i></a>
    <input type="hidden" name="{{$name}}_removed" class="remove-uploaded-image" value="0">
</div>