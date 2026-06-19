<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900" style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">
            {{ __('Profile Dashboard & Settings') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600" style="color: #64748b; margin-bottom: 20px;">
            {{ __("Manage your account details and view your role-based system performance overview.") }}
        </p>
    </header>

    <div style="background: #ffffff; border: 1px solid #e2e8f0; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8;">
                    Current Role:
                </span>
                @if(Auth::user()->role === 'teacher')
                    <span style="background: #eff6ff; color: #1d4ed8; padding: 6px 16px; border-radius: 9999px; font-weight: 700; font-size: 13px; border: 1px solid #bfdbfe;">
                        Teacher / Instructor 🧑‍🏫
                    </span>
                @elseif(Auth::user()->role === 'student')
                    <span style="background: #f0fdf4; color: #15803d; padding: 6px 16px; border-radius: 9999px; font-weight: 700; font-size: 13px; border: 1px solid #bbf7d0;">
                        Student / Learner 🎓
                    </span>
                @else
                    <span style="background: #f8fafc; color: #475569; padding: 6px 16px; border-radius: 9999px; font-weight: 700; font-size: 13px; border: 1px solid #e2e8f0;">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                @endif
            </div>
            
            <span style="font-size: 13px; color: #64748b;">Member since: <strong>{{ Auth::user()->created_at ? Auth::user()->created_at->format('M d, Y') : 'N/A' }}</strong></span>
        </div>

        <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 20px 0;">

        @if(Auth::user()->role === 'teacher')
            <div>
                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Instructor Quick Stats</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600;">Active Quizzes</div>
                        <div style="font-size: 24px; font-weight: 800; color: #1e40af; margin-top: 4px;">Manage System</div>
                    </div>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600;">Reports Generated</div>
                        <div style="font-size: 24px; font-weight: 800; color: #0369a1; margin-top: 4px;">PDF & Excel</div>
                    </div>
                </div>
                <div style="background: #eff6ff; padding: 12px 16px; border-radius: 8px; border: 1px solid #dbeafe;">
                    <a href="{{ route('quizzes.index') }}" style="color: #2563eb; font-weight: 700; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                        Go to Teacher Quiz Control Center &rarr;
                    </a>
                </div>
            </div>

        @elseif(Auth::user()->role === 'student')
            <div>
                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Student Progress Overview</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600;">Available Tests</div>
                        <div style="font-size: 20px; font-weight: 800; color: #166534; margin-top: 4px;">Check Catalog</div>
                    </div>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600;">Performance History</div>
                        <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 4px;">Graded Attempts</div>
                    </div>
                </div>
                <div style="background: #f0fdf4; padding: 12px 16px; border-radius: 8px; border: 1px solid #dcfce7;">
                    <a href="{{ route('quizzes.available') }}" style="color: #16a34a; font-weight: 700; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; margin-right: 20px;">
                        📝 Take Available Quizzes
                    </a>
                    <a href="{{ route('scores.my-scores') }}" style="color: #4b5563; font-weight: 600; text-decoration: underline; font-size: 14px;">
                        View My Grades
                    </a>
                </div>
            </div>
        @endif
    </div>

    <form method="post" action="{{ route('profile.update') }}" style="background: #ffffff; border: 1px solid #e2e8f0; padding: 24px; border-radius: 12px;">
        @csrf
        @method('patch')

        <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.05em;">Account Information Settings</h3>

        <div style="margin-bottom: 16px;">
            <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Full Name</label>
            <input id="name" name="name" type="text" style="width: 100%; border: 1px solid #cbd5e1; padding: 10px 14px; border-radius: 8px; font-size: 14px; color: #334155; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @if($errors->has('name'))
                <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <div style="margin-bottom: 24px;">
            <label for="email" style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Email Address</label>
            <input id="email" name="email" type="email" style="width: 100%; border: 1px solid #cbd5e1; padding: 10px 14px; border-radius: 8px; font-size: 14px; color: #334155; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @if($errors->has('email'))
                <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="submit" style="background: #1e40af; color: #ffffff; padding: 10px 24px; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.15s ease;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#1e40af'">
                Update Details
            </button>

            @if (session('status') === 'profile-updated')
                <span style="color: #16a34a; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    ✅ Settings Updated Successfully
                </span>
            @endif
        </div>
    </form>
</section>
