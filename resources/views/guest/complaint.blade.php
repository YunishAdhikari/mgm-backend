<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Complaint / Feedback | MGM Ops</title>

    <style>
        /* --- CSS Variables --- */
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #e0e7ff;
            --secondary: #ec4899;
            --bg-body: #f3f4f6;
            --bg-card: #ffffff;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --focus-ring: rgba(79, 70, 229, 0.2);
            --success: #10b981;
            --success-bg: #d1fae5;
            --danger: #ef4444;
            --radius: 12px;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* --- Reset & Base --- */
        * { box-sizing: border-box; margin: 0; padding: 0; outline: none; }
        
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* --- Card Container --- */
        .card {
            background: var(--bg-card);
            width: 100%;
            max-width: 680px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            padding: 32px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative top bar */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        /* --- Header --- */
        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 16px;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* --- Success Message --- */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 24px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.4s ease;
        }

        .alert-success {
            background: var(--success-bg);
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        /* --- Form Grid --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* --- Form Groups --- */
        .form-group {
            margin-bottom: 0;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        /* --- Labels --- */
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .optional {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 12px;
            margin-left: 4px;
        }

        /* --- Inputs & Textareas --- */
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            color: var(--text-main);
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        input::placeholder,
        textarea::placeholder {
            color: #9ca3af;
        }

        /* Focus State */
        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px var(--focus-ring);
        }

        /* Select styling fix */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 20px;
            padding-right: 40px;
            cursor: pointer;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* --- File Input Styling --- */
        .file-upload {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .file-upload input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-label {
            font-size: 13px;
            color: var(--text-muted);
            pointer-events: none;
        }

        /* --- Buttons --- */
        .btn-submit {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 28px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.4);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        /* --- Footer Note --- */
        .note {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #9ca3af;
        }

        /* --- Error Text --- */
        .error {
            color: var(--danger);
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
            display: block;
        }

        /* --- Responsive Mobile Styles --- */
        @media (max-width: 640px) {
            body {
                padding: 12px;
                display: block; /* Allows scrolling naturally */
                padding-top: 20px;
            }
            
            .card {
                padding: 24px 20px;
                border-radius: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr; /* Stack to single column */
                gap: 16px;
            }

            .header h1 {
                font-size: 22px;
            }

            /* Make inputs larger for thumbs */
            input, select, textarea {
                padding: 14px 12px;
                font-size: 16px; /* Prevents auto-zoom on iOS */
            }

            .btn-submit {
                padding: 18px;
                font-size: 17px;
            }
        }

        /* Animation */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="header">
            <div class="brand">M</div>
            <h1>Guest Complaint / Feedback</h1>
            <p>We're sorry to hear about your experience. Please let us know how we can help improve your stay.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                
                <!-- Name & Room -->
                <div class="form-group">
                    <label>Your Name <span class="optional">(Optional)</span></label>
                    <input type="text" name="guest_name" placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label>Room Number</label>
                    <input type="text" name="room_number" placeholder="e.g. 305" required>
                </div>

                <!-- Email & Phone -->
                <div class="form-group">
                    <label>Email <span class="optional">(Optional)</span></label>
                    <input type="email" name="email" placeholder="guest@example.com">
                </div>

                <div class="form-group">
                    <label>Phone <span class="optional">(Optional)</span></label>
                    <input type="text" name="phone" placeholder="+1 234 567 890">
                </div>

                <!-- Type & Category -->
                <div class="form-group">
                    <label>Type</label>
                    <select name="type">
                        <option value="complaint">Complaint</option>
                        <option value="feedback">Feedback</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="" disabled selected>Select Category</option>
                        <option value="Room">Room Issue</option>
                        <option value="Housekeeping">Housekeeping</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="F&B">Food & Beverage</option>
                        <option value="Reception">Front Desk</option>
                        <option value="Noise">Noise</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Title (Full Width) -->
                <div class="form-group full-width">
                    <label>Subject / Title</label>
                    <input type="text" name="title" placeholder="Brief summary of the issue" required>
                </div>

                <!-- Description (Full Width) -->
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" placeholder="Please provide details about your experience..."></textarea>
                </div>

                <!-- Priority & Image Upload (Full Width on mobile row) -->
                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Attach Photo <span class="optional">(Optional)</span></label>
                    <div class="file-upload">
                        <input type="file" name="image" accept="image/*">
                        <div class="file-label">
                            <strong>Click to upload</strong> or drag & drop<br>
                            <span style="font-size: 11px; opacity: 0.7">PNG, JPG up to 2MB</span>
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn-submit">
                Submit Complaint
            </button>

            <div class="note">
                Thank you for helping us improve your experience at MGM Ops.
            </div>
        </form>
    </div>

</body>
</html>