import React, {useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {Link, Outlet, useNavigate} from 'react-router-dom';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {icon} from '@fortawesome/fontawesome-svg-core/import.macro';
import login from "../actions/login";
import getArticles from "../actions/getArticles";


export function AuthorisationForm() {
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const userError = useSelector(state => state.user.error);

    const navigate = useNavigate();
    const dispatch = useDispatch();

    const handleLogin = (e) => {
        e.preventDefault();
        dispatch(login({username, password}))
            .then(() => navigate("/blog"))};

    return (
        <div className="form_wrapper">
            <div className="form_container">
                <div className="title_container">
                    <h2>Authorisation</h2>
                </div>
                <div className="row clearfix">
                    <form onSubmit={handleLogin}>
                        {userError && (
                            <span className="text-danger">Проверьте введенные данные</span>)}
                        <div className="input_field">
                                        <span>
                                            <FontAwesomeIcon icon={icon({name: "user", style: "solid"})}/>
                                        </span>
                            <input type="text" name="username" placeholder="Username" required
                                   onChange={e => setUsername(e.target.value)}/>
                        </div>
                        <div className="input_field">
                                        <span>
                                            <FontAwesomeIcon icon={icon({name: "lock", style: "solid"})}/>
                                        </span>
                            <input type="password" name="password" placeholder="Password" required
                                   onChange={e => setPassword(e.target.value)}/>
                        </div>
                        <input className="button" type="submit" value="Sign In"/>
                    </form>
                </div>
            </div>
            <p className="reg-link">No account? <Link to="/registration">Sign Up</Link></p>
            <Outlet/>
        </div>
    );
}
