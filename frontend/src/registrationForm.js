import React from "react";
import { Link, Outlet } from 'react-router-dom';
export function RegistrationForm() {
    return (
        <div className="form_wrapper">
            <div className="form_container">
                <div className="title_container">
                    <h2>Registration</h2>
                </div>
                <div className="row clearfix">
                    <form>
                        <div className="input_field">
                            <span><i aria-hidden="true" className="fa fa-user"></i></span>
                            <input type="text" name="username" placeholder="Username" required/>
                        </div>
                        <div className="input_field"><span><i aria-hidden="true" className="fa fa-lock"></i></span>
                            <input type="password" name="password" placeholder="Password" required/>
                        </div>
                        <div className="input_field"><span><i aria-hidden="true" className="fa fa-lock"></i></span>
                            <input type="password" name="password" placeholder="Re-type Password" required/>
                        </div>
                        <input className="button" type="submit" value="Sign In"/>
                    </form>
                </div>
            </div>
            <p className="reg-link">Already have an account? <Link to="/authorisation">Sign In</Link></p>
            <Outlet />
        </div>
);
}

