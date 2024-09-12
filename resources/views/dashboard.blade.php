<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <!-- <center> -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" >
                        <div> 
                            <h2 style= "text-align: center"class=" font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                {{__("hello, :name", ['name' => Auth::user()->name])}}
                        </div>
                        <br>
                        <div style="display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile Photo" style = "border-radius: 200%">
                        </div>
                        <br>
                        <h4 style= "text-align: center"class=" text-gray-800 dark:text-gray-200 leading-tight">
                            {{__("침묵 끝에 터지는 roarin'
                                막 타기 시작했어 keep burnin'
                                홀린 듯이 dancin' on the table, table
                                Tell 'em I'm pullin' up on sight")}}
                        </h4> 
                        <br>
                        <h4 style= "text-align: center"class=" text-gray-800 dark:text-gray-200 leading-tight">
                            <p>{{ __("You're logged in!") }}</p>    
                        </h4> 
                        
                </div>
            </div>
        </div>
    </div>
<!-- </center> -->
</x-app-layout>
