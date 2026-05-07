import Button from 'react-bootstrap/esm/Button'
import style from './instrutores.module.css'
import { useEffect, useState } from 'react'
import api from '../../api/api';
import Table from 'react-bootstrap/esm/Table';

const Instrutores = () => {

    const [instrutores, setInstrutores] = useState([]);

    const buscarInstrutores = async() => {
        try{
            const res = await api.get('/instrutor')

            if(res.status === 200){
                setInstrutores(res.data.dados)
            }
        }catch(err){
            console.log(err)
        }
    }

    useEffect(() => {
        buscarInstrutores()
    }, [])


    const columns = [
        { header: 'Id do instrutor', accessor: 'id_instrutor'},
        { header: 'Nome', accessor: 'nome' },
        { header: 'Email', accessor: 'email' },
        { header: 'Telefone', accessor: 'telefone' },
        { header: 'Área de atuação', accessor: 'area_atuacao' }
       
    ]

    const temDados = instrutores && instrutores.length > 0

    return (
        <>
         <h1 className={style.titulo}>Instrutores</h1>
         <Button className={style.button}>Novo</Button>
         <Table responsive bordered hover>
                <thead>
                    <tr>
                        {columns.map(columns => (
                            <th key={columns.accessor}>{columns.header}</th>
                        ))}
                    </tr>
                </thead>

                <tbody>
                
                        {instrutores.map(instrutor => (
                            
                            <tr>
                            {columns.map(columns => (
                                <td key={columns.accessor}>{instrutor[columns.accessor]}</td>
                                
                            ))}
                        </tr>
                    
                ))}
            
                </tbody>
            </Table>
        </>
    )
}

export default Instrutores