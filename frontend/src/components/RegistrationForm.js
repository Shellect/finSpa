import React, {useEffect, useState} from "react";
import {Link, useNavigate} from 'react-router-dom';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {icon} from '@fortawesome/fontawesome-svg-core/import.macro';
import {useDispatch, useSelector} from "react-redux";
import register from "../actions/register";
import getArticles from "../actions/getArticles";


export function RegistrationForm() {
    const [passwordError, setPasswordError] = useState(false);
    const [userError, setUserError] = useState(false);
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const {isLoggedIn, error} = useSelector(state => state.user);

    useEffect(() => {
        if (isLoggedIn){
            navigate("/profile");
        }
        if(error){
            setUserError(true);
        }
    })

    const handleRegister = e => {
        e.preventDefault();
        if(password !== confirmPassword){
            setPasswordError(true);
            return;
        }
        dispatch(register({username, password}))
    };
    return (
        <div className="form_wrapper">
            <div className="form_container">
                <div className="title_container">
                    <h2>Registration</h2>
                </div>
                <div className="row clearfix">
                    <form onSubmit={e => handleRegister(e)}>
                        {userError && (
                            <span className="text-danger">Пользователь с таким именем уже существует</span>)}
                        <div className="input_field">
                            <span>
                                <FontAwesomeIcon icon={icon({name: "user", style: "solid"})}/>
                            </span>
                            <input type="text" name="username" placeholder="Username" required
                                   onInput={e => setUsername(e.target.value)}/>
                        </div>
                        {passwordError && (<span className="text-danger">Пароли не совпадают</span>)}
                        <div className="input_field">
                            <span>
                                <FontAwesomeIcon icon={icon({name: "lock", style: "solid"})}/>
                            </span>
                            <input type="password" name="password" placeholder="Password" required
                                   onInput={e => setPassword(e.target.value)}/>
                        </div>
                        <div className="input_field">
                            <span>
                                <FontAwesomeIcon icon={icon({name: "lock", style: "solid"})}/>
                            </span>
                            <input type="password" name="confirmPassword" placeholder="Re-type Password"
                                   required onInput={e => setConfirmPassword(e.target.value)}/>
                        </div>
                        <input className="button" type="submit" value="Sign In"/>
                    </form>
                </div>
            </div>
            <p className="reg-link">Already have an account? <Link to="/authorisation">Sign In</Link></p>
        </div>
    );
}

