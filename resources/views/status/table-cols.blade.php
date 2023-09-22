@if( $col == 'name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Status Name">{{$status->name ?? 'N/A'}}</span>

@elseif( $col == 'slug' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Status Slug">{{$status->slug ?? 'N/A'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-status') && !auth()->user()->can('delete-status') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created Date"> {{ showTwelveHourDateTime($status->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-status','delete-status'])
    <div class="text-end">
        @can('edit-status')
        <a href="{{ route('status.edit', $status->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-status')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $status->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
