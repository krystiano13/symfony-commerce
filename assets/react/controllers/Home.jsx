import React, { useState } from 'react';
import { Layout } from "../components/Layout";
import { productCard } from "../components/ProductCard";
import { DataScroller } from "primereact/datascroller";
import { Card } from "primereact/card";
import { Dropdown } from 'primereact/dropdown';
import { InputText } from "primereact/inputtext";
import { InputNumber } from "primereact/inputnumber";
import styled from "styled-components";

const FilterInputWrapper = styled.div`
    span {
        width: 100%;
        
        input {
            width: 10rem;
        }
    }
`;

const Filters = styled.div`
    margin-top: 1rem;
    position: fixed;
    height: 30rem;
    width: 33%;
    margin-left: 66%;

    @media screen and (max-width: 1023px) {
        width: 100%;
        position: static;
    }
`;

const Main = styled.main`
    display: flex;
    justify-content: space-between;
    
    @media screen and (max-width: 1023px) {
        flex-direction: column-reverse;
    }
`;

export default function Home(props) {
    const [products, setProducts] = useState(JSON.parse(props.products));
    const [sorts, setSorts] = useState([
        "Domyślne",
        "Cena - malejąco",
        "Cena - rosnąco",
        "A - Z",
        "Z - A"
    ]);

    const [selectedSort, setSelectedSort] = useState(sorts[0]);

    return (
        <Layout user={props.user}>
            <Main>
                <DataScroller
                    value={products}
                    itemTemplate={(data) => productCard(data, props.user.id)}
                    inline
                    rows={999}
                    header="Lista Produktów"
                />
                <Filters>
                    <Card
                        title="Filtry"
                        style={{width: "100%", height: "100%"}}
                    >
                        <div style={{
                            display: "flex",
                            alignItems: "center",
                            gap: "1rem",
                            justifyContent: "space-between"
                        }}>
                            <p>Nazwa</p>
                            <InputText style={{width: "12rem"}} placeholder="Nazwa produktu"/>
                        </div>
                        <div
                            style={{
                                display: "flex",
                                alignItems: "center",
                                gap: "1rem",
                                justifyContent: "space-between"
                            }}
                            className="filter-input"
                        >
                            <p>Cena Od</p>
                            <FilterInputWrapper>
                                <InputNumber style={{width: "12rem"}}/>
                            </FilterInputWrapper>
                        </div>
                        <div
                            style={{
                                display: "flex",
                                alignItems: "center",
                                gap: "1rem",
                                justifyContent: "space-between"
                            }}
                            className="filter-input"
                        >
                            <p>Cena Do</p>
                            <FilterInputWrapper>
                                <InputNumber style={{width: "12rem"}}/>
                            </FilterInputWrapper>
                        </div>
                        <div style={{
                            display: "flex",
                            alignItems: "center",
                            gap: "1rem",
                            justifyContent: "space-between"
                        }}>
                            <p>Sortowanie</p>
                            <Dropdown
                                style={{width: "60%"}}
                                value={selectedSort}
                                onChange={(e) => setSelectedSort(e.value)}
                                options={sorts}
                                optionLabel="Sortowanie"
                                placeholder="Wybierz sortowanie"
                                className="w-full md:w-14rem"
                            />
                        </div>
                    </Card>
                </Filters>
            </Main>
        </Layout>
    )
}
