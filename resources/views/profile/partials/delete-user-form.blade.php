<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div style="background-color: #fef2f2; border: 1px solid #fee2e2; padding: 25px; border-radius: 6px; margin-top: 20px;">
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
            @csrf
            @method('delete')

            <h3 class="text-lg font-medium text-red-900" style="color: #991b1b; margin-bottom: 10px;">
                {{ __('Are you sure you want to delete your account?') }}
            </h3>

            <p class="text-sm text-gray-600" style="margin-bottom: 20px;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div>
                <label for="password" class="block font-medium text-sm text-gray-700" style="display: block; margin-bottom: 5px;">
                    {{ __('Password') }}
                </label>
                
                <input 
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 p-2"
                    style="width: 100%; max-width: 450px; border: 1px solid #dc2626; padding: 8px; border-radius: 4px;"
                    placeholder="{{ __('Password') }}"
                    required
                />

                @if($errors->userDeletion->has('password'))
                    <p class="text-sm text-red-600 mt-2" style="color: #dc2626; font-weight: 500;">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif
            </div>

            <div class="mt-6 flex justify-end" style="margin-top: 20px;">
                <button 
                    type="submit" 
                    onclick="return confirm('WARNING: Clicking OK will permanently delete your profile, scores, and access to this online quiz system. This action cannot be undone. Do you wish to proceed?')"
                    style="background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ __('Permanently Delete Account') }}
                </button>
            </div>
        </form>
    </div>
</section>
