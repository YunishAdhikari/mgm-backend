<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Complaint / Feedback</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f7fb;
            padding: 20px;
        }

        .form-wrapper {
            max-width: 720px;
            margin: 0 auto;
            background: white;
            padding: 28px;
            border-radius: 24px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
        }

        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .header h1 {
            color: #1583ff;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
            line-height: 1.5;
        }

        .success-message {
            background: #dcfce7;
            color: #166534;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-weight: bold;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #ddd;
            border-radius: 12px;
            outline: none;
            font-size: 15px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #1583ff;
            box-shadow: 0 0 0 4px rgba(21,131,255,0.12);
        }

        textarea {
            resize: vertical;
            min-height: 130px;
        }

        small {
            color: #dc2626;
            font-weight: bold;
        }

        .submit-btn {
            width: 100%;
            border: none;
            padding: 15px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1583ff, #ff15c4);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .note {
            margin-top: 18px;
            text-align: center;
            color: #777;
            font-size: 13px;
        }

        @media(max-width: 650px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-wrapper {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

<div class="form-wrapper">

    <div class="header">
        <h1>MGM Ops</h1>
        <h2>Guest Complaint / Feedback</h2>
        <p>
            Please share your complaint or feedback with us.
            Our team will review it as soon as possible.
        </p>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('guest.complaint.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="guest_name" value="{{ old('guest_name') }}" placeholder="Optional">
                @error('guest_name') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Room Number</label>
                <input type="text" name="room_number" value="{{ old('room_number') }}" placeholder="Example: 305">
                @error('room_number') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Optional">
                @error('email') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Optional">
                @error('phone') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Type</label>
                <select name="type">
                    <option value="complaint">Complaint</option>
                    <option value="feedback">Feedback</option>
                </select>
                @error('type') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="">Select Category</option>
                    <option value="Room">Room</option>
                    <option value="Housekeeping">Housekeeping</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Food and Beverage">Food and Beverage</option>
                    <option value="Reception">Reception</option>
                    <option value="Noise">Noise</option>
                    <option value="Other">Other</option>
                </select>
                @error('category') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group full">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Short title">
                @error('title') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group full">
                <label>Description</label>
                <textarea name="description" placeholder="Please explain the issue or feedback...">{{ old('description') }}</textarea>
                @error('description') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
                @error('priority') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Upload Image</label>
                <input type="file" name="image">
                @error('image') <small>{{ $message }}</small> @enderror
            </div>

        </div>

        <button type="submit" class="submit-btn">
            Submit
        </button>

        <p class="note">
            Thank you for helping us improve your stay.
        </p>

    </form>

</div>

</body>
</html>