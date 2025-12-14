<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <div class="mb-5">
            <label for="username" class="form-label" :value="__('username')" >Username</label>
            <input id="username" readonly type="text" class="mb-4 form-control" value="{{ old('username', $user->username) }}" required autofocus />
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5">
            <label for="name" class="form-label" :value="__('Name')" >Name</label>
            <input id="name" name="name" type="text" class="mb-4 form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-5">
            <label for="address" class="form-label" :value="__('Address')" >Address</label>
            <input id="address" name="address" type="text" class="mb-4 form-control" value="{{ old('address', $user->address) }}"  autofocus  />
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-5">
            <label for="phone" class="form-label" :value="__('Phone')" >Phone</label>
            <input id="phone" name="phone" type="text" class="mb-4 form-control" value="{{ old('phone', $user->phone) }}"  autofocus />
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5">
            <label for="bloodType" class="form-label" :value="__('Blood Group')" >Blood Group</label>
            <input id="bloodType" name="bloodType" type="text" class="mb-4 form-control" value="{{ old('bloodType', $user->bloodType) }}"  autofocus  />
            @error('bloodType')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-5">
            <label for="email" class="form-label" :value="__('Email')" >Email</label>
            <input id="email" name="email" type="email" class="mb-4 form-control" value="{{ old('email', $user->email) }}"  autocomplete="username" />
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            {{-- <x-primary-button>{{ __('Save') }}</x-primary-button> --}}
            <button class="btn btn-primary mt-10" type="submit"> Save</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
