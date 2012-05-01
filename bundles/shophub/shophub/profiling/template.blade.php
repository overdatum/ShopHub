<!-- ANBU - LARAVEL PROFILER -->
<style type="text/css">{{ file_get_contents(path('sys').'profiling/profiler.css') }}</style>
<div class="anbu">
	<div class="anbu-window">
		<div class="anbu-content-area">
			<div class="anbu-tab-pane anbu-table anbu-log">
				@foreach($requests as $request)

					@if (count($request['logs']) > 0)
						<table>
							<tr>
								<th>Type</th>
								<th>Message</th>
							</tr>
							@foreach ($request['logs'] as $log)
								<tr>
									<td class="anbu-table-first">
										{{ $log[0] }}
									</td>
									<td>
										{{ $log[1] }}
									</td>
							@endforeach
							</tr>
						</table>
					@else
						<span class="anbu-empty">There are no log entries.</span>				
					@endif

				@endforeach
			</div>

			<div class="anbu-tab-pane anbu-table anbu-sql">
				@foreach($requests as $request)

					@if (count($request['queries']) > 0)
						<table>
							<tr>
								<th>Time</th>
								<th>Query</th>
							</tr>
							@foreach ($request['queries'] as $query)
								<tr>
									<td class="anbu-table-first">
										{{ $query[1] }}ms
									</td>
									<td>
										<pre>{{ $query[0] }}</pre>
									</td>
								</tr>
							@endforeach
						</table>
					@else
						<span class="anbu-empty">There have been no SQL queries executed.</span>
					@endif

				@endforeach
			</div>

			<div class="anbu-tab-pane anbu-table anbu-api">
				<table>
					<tr>
						<td style="padding: 0 !important; background: #222;">
							<table style="height: 100%">
								<tr>
									<th>Requests</th>
								</tr>
								<tr>
									<td style="width: 200px">
										<ul>
											<li><a href="#">Current request</a></li>
											<li><a href="#">Previous request</a></li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
						<td style="padding: 0 !important">
							@foreach($requests as $request)

								@if(count($request['api_calls']) > 0)
									<table>
										<tr>
											<th>API Calls</th>
											<th>Response</th>
										</tr>
										@foreach ($request['api_calls'] as $i => $api_call)
										<tr>
											<td class="anbu-table-first">
												<b>{{ $api_call[1] }}</b> <a href="{{ $api_call[2] }}" target="_blank">{{ $api_call[2] }}</a>
											</td>
											<td>
												<b>Code</b><br>
												<pre>{{ $api_call[0] }}</pre>
												@if ( ! is_null($api_call[3]))
												<b>Body</b><br>
												<pre>{{ $api_call[3] }}</pre>
												@endif
												@if ( ! empty($api_call[4]))
												<b>Data</b><br>
												<pre>{{ prettify_json(json_encode($api_call[4])) }}</pre>
												@endif
											</td>
										</tr>
										@endforeach
										</tr>
									</table>
								@else
									<span class="anbu-empty">There have not been any API calls.</span>
								@endif
							@endforeach
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<ul id="anbu-open-tabs" class="anbu-tabs">
		<li>
			<a data-anbu-tab="anbu-log" class="anbu-tab" href="#">Log <span class="anbu-count">{{ count($logs) }}</span></a>
		</li>
		<li>
			<a data-anbu-tab="anbu-sql" class="anbu-tab" href="#">SQL 
				<span class="anbu-count">{{ count($queries) }}</span>
				@if (count($queries))
				<span class="anbu-count">{{ array_sum(array_map(function($q) { return $q[1]; }, $queries)) }}ms</span>
				@endif
			</a>
		</li>
		<li>
			<a data-anbu-tab="anbu-api" class="anbu-tab" href="#">API 
				<span class="anbu-count">{{ count($api_calls) }}</span>
			</a>
		</li>
		<li class="anbu-tab-right"><a id="anbu-hide" href="#">&#8614;</a></li>
		<li class="anbu-tab-right"><a id="anbu-close" href="#">&times;</a></li>
		<li class="anbu-tab-right"><a id="anbu-zoom" href="#">&#8645;</a></li>
	</ul>

	<ul id="anbu-closed-tabs" class="anbu-tabs">
		<li><a id="anbu-show" href="#">&#8612;</a></li>
	</ul>
</div>

<script>window.jQuery || document.write("<script src='//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'>\x3C/script>")</script>
<script>{{ file_get_contents(path('sys').'profiling/profiler.js') }}</script>
<!-- /ANBU - LARAVEL PROFILER -->