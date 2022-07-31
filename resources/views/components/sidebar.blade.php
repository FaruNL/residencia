
@php
    /* Devuelve el primer (y único) rol del usuario autenticado */
    $role = auth()->user()->getRoleNames()->first();
    $route = $role === 'super-admin' ? '' : "{$role}." ;
@endphp

<!-- Barra lateral para móviles -->
<div x-cloak x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">

    <!-- Superposición con opacidad -->
    <div class="fixed inset-0 bg-[#1b396a] bg-opacity-75" aria-hidden="true"
         x-cloak x-show="sidebarOpen" @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <!-- Menú -->
    <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-white"
         x-cloak x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">

        <!-- Botón de cierre -->
        <div class="absolute top-0 right-0 -mr-12 pt-2"
             x-cloak x-show="sidebarOpen"
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <button @click="sidebarOpen = false" type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <x-icon.close class="h-6 w-6 text-white"/>
            </button>
        </div>

        <!-- Logotipo -->
        <div class="flex-shrink-0 flex items-center px-4">
            {{-- SCI --}}
            <img class="w-30" src="{{asset("img/logo.png")}}" alt="Workflow">
        </div>

        <!-- Enlaces de navegación -->
        <div class="mt-5 flex-1 h-0 overflow-y-auto">
            <nav class="px-2 space-y-1">

                <x-sidebar.link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    <x-slot name="icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></x-slot>
                    Inicio
                </x-sidebar.link>

                @can('profile.show')
                    <x-sidebar.link :href='route("user.perfil")' :active="request()->routeIs('user.perfil')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </x-slot>
                        Datos Generales
                    </x-sidebar.link>
                @endcan

                @can('activeinscription.show')
                    <x-sidebar.link :href='route("admin.activeinscription")' :active="request()->routeIs('admin.activeinscription')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </x-slot>
                        Activar Inscripciones
                    </x-sidebar.link>
                @endcan

                @can('studying.show')
                    <x-sidebar.link :href='route("participant.studying")' :active="request()->routeIs('participant.studying')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </x-slot>
                        En curso
                    </x-sidebar.link>
                @endcan

                @can('teaching.show')
                <x-sidebar.link :href='route("instructor.teaching")' :active="request()->routeIs('instructor.teaching')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </x-slot>
                    Cursos instruidos
                </x-sidebar.link>
                @endcan

                {{-- @can('activeinscription.show')
                    <x-sidebar.link :href='route("admin.activeinscription")' :active="request()->routeIs('admin.activeinscription')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </x-slot>
                        Activar Inscripciones
                    </x-sidebar.link>
                @endcan --}}

                @can('inscription.create')
                    <x-sidebar.link :href='route("part.inscription")' :active="request()->routeIs('part.inscription')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </x-slot>
                        Inscripciones
                    </x-sidebar.link>
                @endcan

                @can('user.show')
                    <x-sidebar.dropdown title="Usuarios" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.usuarios")' :active="request()->routeIs('admin.usuarios')">
                            Listar todos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.roles")' :active="request()->routeIs('admin.roles')">
                            Roles
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.participante")' :active="request()->routeIs('admin.participante')">
                            Participantes
                        </x-sidebar.link>
                        {{-- <x-sidebar.link class="pl-11" :href='route("admin.instructores")' :active="request()->routeIs('admin.instructores')">
                            Instructores
                        </x-sidebar.link> --}}
                    </x-sidebar.dropdown>
                @endcan

                @can('area.show')
                <x-sidebar.link :href='route("admin.area")' :active="request()->routeIs('admin.area')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </x-slot>
                    Departamentos
                </x-sidebar.link>
                @endcan

                @can('period.show')
                <x-sidebar.link :href='route("admin.periods-courses")' :active="request()->routeIs('admin.periods-courses')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </x-slot>
                    Periodos
                </x-sidebar.link>
                @endcan

                @can('course.show')
                    <x-sidebar.dropdown title="Cursos" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.group")' :active="request()->routeIs('admin.group')">
                            Listar Grupos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.cursos")' :active="request()->routeIs('admin.cursos')">
                            Listar Cursos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.coursedetail")' :active="request()->routeIs('admin.coursedetail')">
                            Detalles de Cursos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.asignarinstructor")' :active="request()->routeIs('admin.asignarinstructor')">
                            Asignar Instructor
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan

                @can('participantlists.show')
                <x-sidebar.link :href='route("admin.participantLists")' :active="request()->routeIs('admin.participantLists')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </x-slot>
                    Listas
                </x-sidebar.link>
                @endcan

                @can('qualification.edit')
                <x-sidebar.link :href='route("instr.grades")' :active="request()->routeIs('instr.grades')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </x-slot>
                    Asignar Calificaciones
                </x-sidebar.link>
                @endcan

                @can('constancias.show')
                <x-sidebar.dropdown title="Constancias" dp-id="1">
                    <x-slot name="icon" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </x-slot>
                    <x-sidebar.link class="pl-11" :href='route("admin.constancias")' :active="request()->routeIs('admin.constancias')">
                        Participantes
                    </x-sidebar.link>
                    <x-sidebar.link class="pl-11" :href='route("admin.constanciasinstructores")' :active="request()->routeIs('admin.constanciasinstructores')">
                        Instructores
                    </x-sidebar.link>
                </x-sidebar.dropdown>
                 @endcan


                @can('historycourse.show')
                    <x-sidebar.dropdown title="Historial" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </x-slot>

                        <x-sidebar.link class="pl-11" :href='route("admin.historycourse")' :active='request()->route("admin.historycourse")'>
                            Cursos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.historyparticipant")' :active='request()->route("admin.historyparticipant")'>
                            Participantes
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.historyinstructor")' :active='request()->route("admin.historyinstructor")'>
                            Instructores
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan
                @can('sendemail.show')
                    <x-sidebar.dropdown title="Envio de Notificaciones" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.email")' :active='request()->route("admin.email")'>
                            Notificaciones via email
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.post")' :active='request()->route("admin.post")'>
                            Notificaciones en el sistema
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan

                <x-sidebar.link href="{{route('post.index')}}">
                    <x-slot name="icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></x-slot>
                    Notificationes recibidas
                </x-sidebar.link>
            </nav>
        </div>
    </div>

    <!-- Elemento ficticio para obligar a la barra lateral a encogerse para ajustarse al icono de cierre -->
    <div class="flex-shrink-0 w-14" aria-hidden="true">
    </div>
</div>

<!-- Barra lateral para escritorio -->
<div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">

    <!-- Menú -->
    <div class="flex flex-col flex-grow border-r border-gray-200 pt-5 bg-white overflow-y-auto">

        <!-- Logotipo -->
        <div class="flex text-center justify-center flex-shrink-0 px-4">
            {{-- {-- SCI --} --}}
            <img class="w-30" src="{{asset("img/logo.png")}}" alt="Workflow">
    </div>

        <!-- Enlaces de navegación -->
        <div class="mt-5 flex-1 h-0 overflow-y-auto">
            <nav class="px-2 space-y-1">

                <x-sidebar.link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    <x-slot name="icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></x-slot>
                    Inicio
                </x-sidebar.link>

                @can('profile.show')
                    <x-sidebar.link :href='route("user.perfil")' :active="request()->routeIs('user.perfil')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </x-slot>
                        Datos Generales
                    </x-sidebar.link>
                @endcan

                @can('activeinscription.show')
                    <x-sidebar.link :href='route("admin.activeinscription")' :active="request()->routeIs('admin.activeinscription')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </x-slot>
                        Activar Inscripciones
                    </x-sidebar.link>
                @endcan

                @can('studying.show')
                    <x-sidebar.link :href='route("participant.studying")' :active="request()->routeIs('participant.studying')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </x-slot>
                        En curso
                    </x-sidebar.link>
                @endcan

                @can('teaching.show')
                <x-sidebar.link :href='route("instructor.teaching")' :active="request()->routeIs('instructor.teaching')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </x-slot>
                    Cursos instruidos
                </x-sidebar.link>
                @endcan

                {{-- @php
                $consulta =
                @endphp --}}


                @can('inscription.create')
                    <x-sidebar.link :href='route("part.inscription")' :active="request()->routeIs('part.inscription')">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </x-slot>
                        Inscripciones
                    </x-sidebar.link>
                @endcan

                @can('user.show')
                    <x-sidebar.dropdown title="Usuarios" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.usuarios")' :active="request()->routeIs('admin.usuarios')">
                            Listar todos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.roles")' :active="request()->routeIs('admin.roles')">
                            Roles
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.participante")' :active="request()->routeIs('admin.participante')">
                            Participantes
                        </x-sidebar.link>
                        {{-- <x-sidebar.link class="pl-11" :href='route("admin.instructores")' :active="request()->routeIs('admin.instructores')">
                            Instructores
                        </x-sidebar.link> --}}
                    </x-sidebar.dropdown>
                @endcan

                @can('area.show')
                <x-sidebar.link :href='route("admin.area")' :active="request()->routeIs('admin.area')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </x-slot>
                    Departamentos
                </x-sidebar.link>
                @endcan

                @can('period.show')
                <x-sidebar.link :href='route("admin.periods-courses")' :active="request()->routeIs('admin.periods-courses')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </x-slot>
                    Periodos
                </x-sidebar.link>
                @endcan

                {{-- @can('user_show')
                <x-sidebar.dropdown title="Grupos" dp-id="1">
                    <x-slot name="icon" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </x-slot>
                    <x-sidebar.link class="pl-11" :href='route("admin.group")' :active='request()->route("admin.group")'>
                        Mostrar
                    </x-sidebar.link>
                </x-sidebar.dropdown>
                @endcan --}}



                @can('course.show')
                    <x-sidebar.dropdown title="Cursos" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.group")' :active="request()->routeIs('admin.group')">
                            Listar Grupos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.cursos")' :active="request()->routeIs('admin.cursos')">
                            Listar Cursos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.coursedetail")' :active="request()->routeIs('admin.coursedetail')">
                            Detalles de Cursos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.asignarinstructor")' :active="request()->routeIs('admin.asignarinstructor')">
                            Asignar Instructor
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan

                @can('participantlists.show')
                <x-sidebar.link :href='route("admin.participantLists")' :active="request()->routeIs('admin.participantLists')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </x-slot>
                    Listas
                </x-sidebar.link>
                @endcan

                {{-- @can('user_show')
                    <x-sidebar.dropdown title="Departamentos" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.area")' :active='request()->route("admin.area")'>
                            Mostrar
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan --}}

                @can('qualification.edit')
                <x-sidebar.link :href='route("instr.grades")' :active="request()->routeIs('instr.grades')">
                    <x-slot name="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </x-slot>
                    Asignar Calificaciones
                </x-sidebar.link>
                @endcan


                @can('constancias.show')
                    <x-sidebar.dropdown title="Constancias" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.constancias")' :active="request()->routeIs('admin.constancias')">
                            Participantes
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.constanciasinstructores")' :active="request()->routeIs('admin.constanciasinstructores')">
                            Instructores
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan


                @can('historycourse.show')
                    <x-sidebar.dropdown title="Historial" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </x-slot>

                        <x-sidebar.link class="pl-11" :href='route("admin.historycourse")' :active='request()->route("admin.historycourse")'>
                            Cursos
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.historyparticipant")' :active='request()->route("admin.historyparticipant")'>
                            Participantes
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.historyinstructor")' :active='request()->route("admin.historyinstructor")'>
                            Instructores
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan

                @can('sendemail.show')
                    <x-sidebar.dropdown title="Envio de Notificaciones" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.email")' :active='request()->route("admin.email")'>
                            Notificaciones via email
                        </x-sidebar.link>
                        <x-sidebar.link class="pl-11" :href='route("admin.post")' :active='request()->route("admin.post")'>
                            Notificaciones en el sistema
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan

                {{-- @can('user_show')
                    <x-sidebar.dropdown title="Constancias" dp-id="1">
                        <x-slot name="icon" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></x-slot>
                        <x-sidebar.link class="pl-11" :href='route("admin.constancias")' :active='request()->route("admin.constancias")'>
                            Generar
                        </x-sidebar.link>
                    </x-sidebar.dropdown>
                @endcan --}}

                {{-- <x-sidebar.link href="#">
                    <x-slot name="icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></x-slot>
                    Reports
                </x-sidebar.link> --}}

                <x-sidebar.link href="{{route('post.index')}}">
                    <x-slot name="icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" clip-rule="evenodd" /></x-slot>
                    Notificationes recibidas
                </x-sidebar.link>
            </nav>
        </div>
    </div>
</div>
