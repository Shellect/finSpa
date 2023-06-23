import React, {useState} from "react";
import axios from "axios";
import {useNavigate} from "react-router-dom";

export default function CreateArticle() {
    const [title, setTitle] = useState("");
    const [content, setContent] = useState("");
    const navigate = useNavigate();

    const handleSubmit = e => {
        e.preventDefault();
        const authToken = JSON.parse(localStorage.getItem("user")).authToken;
        axios.post("https://spa.local/app/?r=articles/create", {
           title, content, authToken
        }).then(
            response => {
                if(response.data.status === 'Success'){
                    navigate("/articles");
                }
            }
        );
    }

    return (
        <div className="container">
            <div className="row">
                <div className="col-12">
                    <form className="bg-light"  onSubmit={handleSubmit}>
                        <div className="form-group">
                            <input id="title" type="text" className="form-control" placeholder="Название" onChange={e => setTitle(e.target.value)}/>
                        </div>
                        <div className="form-group">
                            <textarea id="content" cols="30" rows="10" className="form-control" placeholder="Текст..." onChange={e => setContent(e.target.value)}></textarea>
                        </div>
                        <div className="d-grid">
                            <input type="submit" className="btn btn-warning" value="Сохранить"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}