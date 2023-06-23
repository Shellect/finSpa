import React, {useEffect} from "react";
import {useDispatch, useSelector} from "react-redux";
import Article from "../components/Article";
import getArticles from "../actions/getArticles";

export default function BlogPage() {
    const {loading, articles} = useSelector(state => state.articles);
    const dispatch = useDispatch();

    useEffect(() => {
        console.log(loading);
        console.log(articles);
        if(!loading){
            dispatch(getArticles());
        }
    }, [loading]);

    return (
        <div className="container">
            <div className="row">
                <div className="col-12">
                    {articles && articles.map(
                        (article, i) => <Article key={i} title={article.title} content={article.content}/>
                    )}
                </div>
            </div>
        </div>
    );
}