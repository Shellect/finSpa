import React from "react";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {icon} from '@fortawesome/fontawesome-svg-core/import.macro';

export function HomePage() {
    return (
        <div className="homepage">
            <div className="container">
                <div className="row">
                    <h2 className="mt-3 text-white">Fin SPA - Приложение для учета финансов</h2>
                    <div className="col-sm-12 col-lg-6 mt-3">
                        <div className="row d-flex h-100 align-items-center justify-content-start">
                            <div className="col-6">
                                <div className="card">
                                    <div className="card-body">
                                        <FontAwesomeIcon icon={icon({name: "cloud-arrow-up", style: "solid"})}/>
                                        <h5 className="card-title">Облачное решение</h5>
                                    </div>
                                </div>
                            </div>
                            <div className="col-6">
                                <div className="card">
                                    <div className="card-body">
                                        <FontAwesomeIcon icon={icon({name: "hard-drive", style: "solid"})}/>
                                        <h5 className="card-title">Оптимизация скорости</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-6">
                                <div className="card">
                                    <div className="card-body">
                                        <FontAwesomeIcon icon={icon({name: "list-check", style: "solid"})}/>
                                        <h5 className="card-title">Онлайн маркетинг</h5>
                                    </div>
                                </div>
                            </div>
                            <div className="col-6">
                                <div className="card">
                                    <div className="card-body">
                                        <FontAwesomeIcon icon={icon({name: "code", style: "solid"})}/>
                                        <h5 className="card-title">Дизайн сайта</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-sm-12 col-lg-6">

                    </div>
                </div>
            </div>
        </div>
    );

}