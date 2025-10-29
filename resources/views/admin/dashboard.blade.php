<x-layout>
    <h2 class="dashboard-page-title">Dashboard</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    <div class="meta-ads-section glass-card">
        <h3 class="section-title">Meta Ads</h3>

        <form class="filters-grid"s>

            <div class="filter-group">
                <div class="filter-Agroup">
                    <x-multiselect A
                        name="creatives" 
                        label="Criativos" 
                        :options="$allCreatives" 
                        :selected="request('creatives', [])" 
                        placeholder="Selecione um ou mais criativos">
                    </x-multiselect>
                </div>

                {{-- <select id="produto" name="creatives[]" class="custom-select" multiple>
                    @foreach ($allCreatives as $creative)
                        <option value="{{ $creative }}">{{ $creative }}</option>
                    @endforeach
                </select> --}}
            </div>
            <div class="filter-group">
                <label for="editor">Editor</label>
                <select id="editor" class="custom-select">
                    <option>Editor A</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="copywriter">Copywriter</label>
                <select id="copywriter" class="custom-select">
                    <option>Copywriter R</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="sources">Conta</label>
                <select id="sources" name="sources" class="custom-select">
                    <option value="">Selecione ...</option>
                    @foreach ($allSources as $source )
                        <option value="{{ $source }}" @if($source === request('sources')) selected @endif>{{$source}} </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-submit-area">
                <button type="submit" class="btn-filter">FILTRAR</button>
            </div>
        </form>
    </div>


    <div class="best-creatives-section glass-card">
        <h3 class="section-title">Melhores Criativos</h3>

        <div class="table-responsive">
            <table class="creatives-table">
                <thead>
                    <tr>
                        <th class="col-ad">AD</th>
                        {{-- <th>INICIOU EM</th>
                        <th>ANUNCIOU ATÉ</th> --}}
                        <th class="col-roas">ROAS</th>
                        <th>LUCRO BRUTO</th>
                        <th>INVESTIMENTO</th>
                        <th>RECEITA</th>
                        <th>CPC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topCreatives as $top)
                        <tr>
                            <td>
                                <p class="ad-name">{{ $top->creative_code }}</p>
                                <span class="ad-link">{{ $top->source }}</span>
                            </td>
                            <td>{{ $top->roi * 100 }}%</td>
                            <td>@real($top->profit)</td>
                            <td>@real($top->cost)</td>
                            <td>@real($top->cost + $top->profit)</td>
                            <td>?</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</x-layout>
