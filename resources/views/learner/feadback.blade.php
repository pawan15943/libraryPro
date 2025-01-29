@extends('layouts.admin')
@section('content')


<div class="card mb-4">
    <div class="row">
        <h4 class="mb-4">Library Feedback Form</h4>

        <!-- Name -->
        <div class="col-lg-6">
            <label for="name">Name</label>
            <input type="text" id="name" class="form-control" placeholder="Enter your name">
        </div>

        <!-- Student ID -->
        <div class="col-lg-6">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" class="form-control" placeholder="Enter your student ID">
        </div>

        <!-- Grade/Department -->
        <div class="col-lg-6 mt-4">
            <label for="grade">Grade/Department</label>
            <input type="text" id="grade" class="form-control" placeholder="Enter your grade or department">
        </div>

        <!-- Contact Information -->
        <div class="col-lg-6 mt-4">
            <label for="contact">Contact Information (Optional)</label>
            <input type="text" id="contact" class="form-control" placeholder="Enter your contact info (optional)">
        </div>

        <!-- Frequency of Visits -->
        <div class="col-lg-6 mt-4">
            <label for="frequency">How often do you visit the library?</label>
            <select id="frequency" class="form-control">
                <option value="">Select...</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="occasionally">Occasionally</option>
                <option value="rarely">Rarely</option>
            </select>
        </div>

        <!-- Purpose of Visit -->
        <div class="col-lg-6 mt-4">
            <label for="purpose">Why do you usually visit the library?</label>
            <input type="text" id="purpose" class="form-control" placeholder="e.g., Borrow books, Study, Research">
        </div>

        <!-- Resource Availability -->
        <div class="col-lg-12 mt-4">
            <label for="resources">Are the books/resources you need usually available?</label>
            <select id="resources" class="form-control">
                <option value="">Select...</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
                <option value="partially">Partially</option>
            </select>
        </div>

        <!-- Resource Suggestions -->
        <div class="col-lg-12 mt-4">
            <label for="resource_suggestions">Suggestions for new resources or books:</label>
            <textarea id="resource_suggestions" class="form-control" rows="3" placeholder="Enter your suggestions"></textarea>
        </div>

        <!-- Library Environment -->
        <div class="col-lg-12 mt-4">
            <label for="environment">How would you rate the library environment? (Rating 1-5)</label>
            <input type="number" id="environment" class="form-control" placeholder="Rate (1 to 5)" min="1" max="5">
        </div>

        <!-- Library Staff -->
        <div class="col-lg-12 mt-4">
            <label for="staff">Are the library staff helpful and approachable?</label>
            <select id="staff" class="form-control">
                <option value="">Select...</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
                <option value="sometimes">Sometimes</option>
            </select>
        </div>

        <!-- Seating and Space -->
        <div class="col-lg-12 mt-4">
            <label for="seating">Is there enough seating space in the library?</label>
            <select id="seating" class="form-control">
                <option value="">Select...</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>

        <!-- Digital Resources -->
        <div class="col-lg-12 mt-4">
            <label for="digital_resources">Are you satisfied with the digital resources (e-books, journals, internet)?</label>
            <select id="digital_resources" class="form-control">
                <option value="">Select...</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
                <option value="needs_improvement">Needs Improvement</option>
            </select>
        </div>

        <!-- Ease of Access -->
        <div class="col-lg-12 mt-4">
            <label for="access">Is the catalog/search system easy to use?</label>
            <select id="access" class="form-control">
                <option value="">Select...</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
                <option value="needs_improvement">Needs Improvement</option>
            </select>
        </div>

        <!-- Additional Comments -->
        <div class="col-lg-12 mt-4">
            <label for="comments">What do you like most about the library? Any suggestions?</label>
            <textarea id="comments" class="form-control" rows="3" placeholder="Enter your comments"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="col-lg-3 mt-4">
            <button type="submit" class="btn btn-primary button">Submit Feedback</button>
        </div>
    </div>



</div>




@endsection