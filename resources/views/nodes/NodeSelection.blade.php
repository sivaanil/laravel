<div id="nodeSelectionContainer" class="node-selection-container">
	
	<div class="row" style="max-width:100%">
		<?php $curRow = 1;?>
		<?php
        foreach ($nodeOptions as $row): array_map('htmlentities', $row); ?>
			<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 nodesTree {{(isset($row['isCurrent'])) ? 'currentNode' : 'notCurrentNode' }}" >
				<div id="radioDiv" class=" text-center">
					<input type="radio" class='sev_{{$row['sev'].'_'.$row['isGroup']}} {{$row['isGroup']}} {{(isset($row['hasChildren'])) ? $row['hasChildren'] : null }}' name="group1" value="{{$row['nodeIdNum']}}" {{(isset($row['isCurrent'])) ? 'checked="checked"' : 'notCurrentNode' }}>
				</div>
				<div class="nodesTreeLinkContainer" onclick="window.location='{{url()}}/home#/nodes/{{$row['nodeId']}}'">
					<div class="nodesTreeLinkSubContainer">
						<div class="nodesTreeLinkSubContainer2"><a href="{{url()}}/home#/nodes/{{$row['nodeId']}}">{{{$row['name']}}}	</a></div>
					</div>
				</div>
			</div>
		<?php $curRow++; ?>
		<?php endforeach; ?>
	</div>

</div>